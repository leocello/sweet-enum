<?php

namespace Leocello\SweetEnum;

/**
 * @mixin \BackedEnum
 * @mixin SweetEnumContract
 */
trait SweetEnum
{
    /**
     * Checks if this enum case is the case passed (or one of the cases passed in case of an array)
     *
     * @param  \BackedEnum|\BackedEnum[]  $enum
     */
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

    /**
     * Returns case ID (alias of `->value`)
     */
    public function id(): string
    {
        return $this->value;
    }

    /**
     * Returns case name (alias of `->name`)
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns case title defined in the attributes (default: case name)
     */
    public function title(): string
    {
        if (is_null($this->getEnumCaseAttribute()->title)) {
            return $this->name();
        }

        return $this->getEnumCaseAttribute()->title;
    }

    /**
     * Returns the value of `isOn` defined in the attributes (default: `true`)
     */
    public function isOn(): bool
    {
        return $this->getEnumCaseAttribute()->isOn;
    }

    //---

    /**
     * Checks if case has a case class associated
     */
    public function hasClass(): bool
    {
        return ! is_null($this->getClassName());
    }

    /**
     * Returns the case class name if case has one associated, otherwise `null`
     */
    public function getClassName(): ?string
    {
        return $this->getEnumCaseAttribute()->caseClass;
    }

    /**
     * Returns an instance of the case class if case has one associated, otherwise `null`
     */
    public function getClassInstance(): ?SweetClass
    {
        if (! $this->hasClass()) {
            return null;
        }

        $class = $this->getClassName();

        return new $class($this);
    }

    //---

    /**
     * Returns the enum case info as an array with the fields or fields type passed
     *
     * @param  array|string  $fields
     *                                Values accepted:
     *                                - self::FIELDS_ORIGINAL
     *                                - self::FIELDS_SWEET_BASIC
     *                                - self::FIELDS_SWEET_WITH_STATUS
     *                                - self::FIELDS_SWEET_FULL
     *                                - custom array with values
     */
    public function toArray(array|string $fields = self::FIELDS_SWEET_BASIC): array
    {
        if (is_array($fields)) {
            if (count($fields) < 1) {
                throw new \InvalidArgumentException('You need to pass at least one field in array');
            }

            $computed = $this->getComputedFields();

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

                foreach ($this->getComputedFields() as $key => $value) {
                    $output[$key] = $value;
                }

                return $output;
        }

        throw new \InvalidArgumentException('Fields argument is invalid');
    }

    //---

    /**
     * Returns all enum's cases (similar to ::cases() but with the ability to control by status - only active by default)
     *
     * @return array<int, static>
     */
    public static function getCases(bool $onlyActive = true): array
    {
        $output = [];

        foreach (static::cases() as $case) {
            if (! $onlyActive || $case->isOn()) {
                $output[] = $case;
            }
        }

        return $output;
    }

    /**
     * Get all cases' info based on fields / fields type and status passed (default: only active)
     *
     * @return array<string, array>
     */
    public static function getCasesInfo(array|string $fields = self::FIELDS_SWEET_BASIC, bool $onlyActives = true): array
    {
        return static::map(fn (SweetEnumContract $case) => $case->toArray($fields), $onlyActives);
    }

    /**
     * Returns the default enum case (if none defined than gets the first case described)
     */
    public static function getDefaultCase(): static
    {
        $default = static::DEFAULT ?? null;

        if (! is_null($default)) {
            return static::from($default->id());
        }

        return static::cases()[0];
    }

    //---

    /**
     * Return an array of computed fields for passed case
     */
    protected function getComputedFields(): array
    {
        return [];
    }

    /**
     * @param  \Closure(static $case):void  $callback
     */
    public static function foreach(\Closure $callback, bool $onlyActives = true): void
    {
        foreach (static::getCases($onlyActives) as $case) {
            $callback($case);
        }
    }

    /**
     * @param  \Closure(static $case):mixed  $callback
     * @return array<string, mixed>
     */
    public static function map(\Closure $callback, bool $onlyActives = true): array
    {
        $output = [];

        foreach (static::getCases($onlyActives) as $case) {
            $output[$case->id()] = $callback($case);
        }

        return $output;
    }

    //---

    private function getEnumCaseAttribute(): ?SweetCase
    {
        return static::enumCaseAttributes()[$this] ?? null;
    }

    /**
     * @return \SplObjectStorage<\UnitEnum, SweetCase>
     */
    private static function enumCaseAttributes(): \SplObjectStorage
    {
        static $attributes;

        if (! isset($attributes)) {
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

        if (! isset($extras)) {
            $extras = new \SplObjectStorage();

            foreach (static::cases() as $case) {
                $extras[$case] = $case->getEnumCaseAttribute()?->custom;
            }
        }

        return $extras;
    }

    protected function getCustomValue(string $key, bool $throwOnMissingExtra = false): mixed
    {
        if (! isset(static::arrayAccessibleCustom()[$this][$key])) {
            $computed = $this->getComputedFields();

            if (isset($computed[$key])) {
                return $computed[$key];
            }

            if (! $throwOnMissingExtra) {
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
