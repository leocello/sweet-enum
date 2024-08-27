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

    /** @deprecated - please use `SweetFields::Original` */
    public const FIELDS_ORIGINAL = 'original'; // enum original values (value / name)

    /** @deprecated - please use `SweetFields::Basic` */
    public const FIELDS_BASIC = 'basic'; // only id / title

    /** @deprecated - please use `SweetFields::BasicWithStatus` */
    public const FIELDS_BASIC_WITH_STATUS = 'basic-with-status'; // only id / title / isOn

    /** @deprecated - please use `SweetFields::Sweet` */
    public const FIELDS_SWEET = 'sweet'; // All custom and computed fields - but without status or the original (value / name)

    /** @deprecated - please use `SweetFields::Full` */
    public const FIELDS_FULL = 'full'; // All fields including original, custom and computed

    /** @deprecated - please use `SweetFields::Basic` */
    public const FIELDS_SWEET_BASIC = self::FIELDS_BASIC;

    /** @deprecated - please use `SweetFields::BasicWithStatus` */
    public const FIELDS_SWEET_WITH_STATUS = self::FIELDS_BASIC_WITH_STATUS;

    /** @deprecated - please use `SweetFields::Full` */
    public const FIELDS_SWEET_FULL = self::FIELDS_FULL;
}
