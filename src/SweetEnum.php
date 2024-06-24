<?php

namespace Leocello\SweetEnum;

use JetBrains\PhpStorm\ArrayShape;

/**
 * @mixin \BackedEnum
 * @mixin SweetEnumContract
 */
trait SweetEnum
{
    /** @param \BackedEnum|\BackedEnum[] $enum */
    public function is(\BackedEnum|array $enum): bool
    {
        if (is_array($enum)) {
            foreach ($enum as $enumItem) {
                if ($this->value == $enumItem->value) {
                    return true;
                }
            }

            return false;
        }

        return $enum->value == $this->value;
    }

    public function id(): string
    {
        return $this->value;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function title(): string
    {
        if (is_null($this->getEnumCaseAttribute()->title)) {
            return $this->name();
        }

        return $this->getEnumCaseAttribute()->title;
    }

    public function isOn(): bool
    {
        return $this->getEnumCaseAttribute()->isOn;
    }

    //---

    public function hasClass(): bool
    {
        return !is_null($this->getClassName());
    }

    public function getClassName(): string|null
    {
        return $this->getEnumCaseAttribute()->caseClass;
    }

    public function getClassInstance(): SweetClass|null
    {
        if (!$this->hasClass()) {
            return null;
        }

        $class = $this->getClassName();

        return new $class($this);
    }

    //---

    public function toArray(array|string $fields = self::FIELDS_SWEET_BASIC): array
    {
        if (is_array($fields)) {
            if (count($fields) < 1) {
                throw new \InvalidArgumentException('You need to pass at least one field in array');
            }

            $computed = static::computedFields($this);

            $output = [];

            foreach ($fields as $field) {
                if (isset($computed[$field])) {
                    $output[$field] = $computed[$field];

                    continue;
                }

                $output[$field] = $this->{$field}();
            }

            return $output;
        }

        switch ($fields) {
            case self::FIELDS_ORIGINAL:
                return [
                    'value' => $this->value,
                    'name' => $this->name,
                ];
            case self::FIELDS_SWEET_BASIC:
                return [
                    'id' => $this->id(),
                    'title' => $this->title(),
                ];
            case self::FIELDS_SWEET_WITH_STATUS:
                return [
                    'isOn' => $this->isOn(),
                    'id' => $this->id(),
                    'title' => $this->title(),
                ];
            case self::FIELDS_SWEET_FULL:
                $output = [
                    'isOn' => $this->isOn(),
                    'value' => $this->value,
                    'id' => $this->id(),
                    'name' => $this->name,
                    'title' => $this->title(),
                ];

                foreach (static::arrayAccessibleCustom()[$this] as $key => $value) {
                    $output[$key] = $value;
                }

                foreach (static::computedFields($this) as $key => $value) {
                    $output[$key] = $value;
                }

                return $output;
        }

        throw new \InvalidArgumentException('Fields argument is invalid');
    }

    //---

    /**
     * @return static[]
     */
    public static function getCases(bool $onlyActive = true): array
    {
        $output = [];

        foreach (static::cases() as $case) {
            if (!$onlyActive || $case->isOn()) {
                $output[] = $case;
            }
        }

        return $output;
    }

    public static function getCasesInfo(array|string $fields = self::FIELDS_SWEET_BASIC, bool $onlyActives = true): array
    {
        $output = [];

        foreach (static::getCases($onlyActives) as $case) {
            $output[] = $case->toArray($fields);
        }

        return $output;
    }

    public static function getDefaultCase(): static
    {
        $default = static::DEFAULT ?? null;

        if (!is_null($default)) {
            return static::from($default->id());
        }

        return static::cases()[0];
    }

    //---

    public static function computedFields(SweetEnumContract $item): array
    {
        return [];
    }

    public static function foreach(\Closure $callback, bool $onlyActives = true): array
    {
        /// TODO:
        ///  - For each case (only active?) run callback and add collect its return
        ///  - The array should have as key the enum and as value the return of the callback

        return [];
    }

    //---

    private function getEnumCaseAttribute(): SweetCase|null
    {
        return static::enumCaseAttributes()[$this] ?? null;
    }

    /**
     * @return \SplObjectStorage<\UnitEnum, SweetCase>
     */
    private static function enumCaseAttributes(): \SplObjectStorage
    {
        static $attributes;

        if (!isset($attributes)) {
            $attributes = new \SplObjectStorage();

            foreach ((new \ReflectionEnum(static::class))->getCases() as $rCase) {
                if (null === $rAttr = $rCase->getAttributes(SweetCase::class)[0] ?? null) {
                    continue;
                }

                /** @var SweetCase $attr */
                $attr = $rAttr->newInstance();

                $attributes[$rCase->getValue()] = $attr;
            }
        }

        return $attributes;
    }

    private static function arrayAccessibleCustom(): \SplObjectStorage
    {
        static $extras;

        if (!isset($extras)) {
            $extras = new \SplObjectStorage();

            foreach (static::cases() as $case) {
                $extras[$case] = $case->getEnumCaseAttribute()?->custom;
            }
        }

        return $extras;
    }

    protected function getCustomValue(string $key, bool $throwOnMissingExtra = false): mixed
    {
        if (!isset(static::arrayAccessibleCustom()[$this][$key])) {
            $computed = static::computedFields($this);

            if (isset($computed[$key])) {
                return $computed[$key];
            }

            if (!$throwOnMissingExtra) {
                return null;
            }

            throw new \InvalidArgumentException(sprintf('No value for extra "%s" for enum case %s::%s', $key, __CLASS__, $this->name));
        }

        return static::arrayAccessibleCustom()[$this][$key] ?? null;
    }

    public function __call(string $name, array $arguments): mixed
    {
        if ($this->hasClass()) {
            $instance = $this->getClassInstance();

            if (method_exists($instance, $name)) {
                return $instance->{$name}(...$arguments);
            }
        }

        return $this->getCustomValue($name, true);
    }
}
