<?php

namespace Leocello\SweetEnum;

/**
 * @mixin \BackedEnum
 */
interface SweetEnumContract
{
    /**
     * @var static|null
     */
    public const DEFAULT = null;

    public const DEFAULT_CASE_CLASS = null;

    public const FIELDS_ORIGINAL = 'original'; // enum original values (value / name)

    public const FIELDS_BASIC = 'basic'; // only id / title

    public const FIELDS_BASIC_WITH_STATUS = 'basic-with-status'; // only id / title / isOn

    public const FIELDS_SWEET = 'sweet'; // All custom and computed fields - but without status or the original (value / name)

    public const FIELDS_FULL = 'full'; // All fields including original, custom and computed

    /** @deprecated - please use `FIELDS_BASIC` */
    public const FIELDS_SWEET_BASIC = self::FIELDS_BASIC;

    /** @deprecated - please use `FIELDS_BASIC_WITH_STATUS` */
    public const FIELDS_SWEET_WITH_STATUS = self::FIELDS_BASIC_WITH_STATUS;

    /** @deprecated - please use `FIELDS_FULL` */
    public const FIELDS_SWEET_FULL = self::FIELDS_FULL;
}
