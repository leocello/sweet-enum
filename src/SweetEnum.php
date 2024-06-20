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
        $ret = [];

        /// TODO

        return $ret;
    }

    //---

    /**
     * @return static[]
     */
    public static function getCases(bool $onlyActive = true): array
    {
        $ret = [];

        foreach (static::cases() as $case) {
            if (!$onlyActive || $case->isOn()) {
                $ret[] = $case;
            }
        }

        return $ret;
    }

    public static function getCasesInfo(bool $onlyActives = true, array|string $fields = self::FIELDS_SWEET_BASIC): array
    {
        $ret = [];

        foreach (static::getCases($onlyActives) as $case) {
            $ret[] = $case->toArray($fields);
        }

        return $ret;
    }

    public static function getDefaultCase(): static
    {
        $default = static::DEFAULT ?? null;

        if (!is_null($default)) {
            return static::from($default->id());
        }

        return static::cases()[0];
    }

    public static function computedFields(SweetEnumContract $item): array
    {
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
        if ($throwOnMissingExtra && !isset(static::arrayAccessibleCustom()[$this][$key]) && !is_null(static::arrayAccessibleCustom()[$this][$key])) {
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
