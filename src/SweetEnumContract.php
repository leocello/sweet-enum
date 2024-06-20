<?php

namespace Leocello\SweetEnum;

interface SweetEnumContract
{
    const DEFAULT = null;

    const FIELDS_ORIGINAL = 'original'; // enum original values (value / name)
    const FIELDS_SWEET_BASIC = 'sweet-basic'; // only id / title
    const FIELDS_SWEET_WITH_STATUS = 'sweet-with-status'; // only id / title / isOn
    const FIELDS_SWEET_FULL = 'sweet-full'; // All fields including custom and computed

    /** @param \BackedEnum|\BackedEnum[] $enum */
    public function is(\BackedEnum|array $enum): bool;
    public function id(): string;
    public function name(): string;
    public function title(): string;
    public function isOn(): bool;

    public function hasClass(): bool;
    public function getClassName(): ?string;
    public function getClassInstance(): SweetClass|null;

    /**
     * @param array|string $fields
     *      Values accepted:
     *       - self::FIELDS_ORIGINAL
     *       - self::FIELDS_SWEET_BASIC
     *       - self::FIELDS_SWEET_WITH_STATUS
     *       - self::FIELDS_SWEET_FULL
     *       - custom array with values
     */
    public function toArray(array|string $fields = self::FIELDS_SWEET_BASIC);

    public static function getCases(bool $onlyActives = true): array;
    public static function getCasesInfo(array|string $fields = self::FIELDS_SWEET_BASIC, bool $onlyActives = true): array;
    public static function getDefaultCase(): static;

    public static function computedFields(SweetEnumContract $item): array;
    public static function foreach(\Closure $callback, bool $onlyActives = true): array;

    /// TODO: Add more collection methods
}
