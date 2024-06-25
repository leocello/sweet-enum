<?php

namespace Leocello\SweetEnum;

interface SweetEnumContract
{
    /**
     * @var static|null
     */
    public const DEFAULT = null;

    public const FIELDS_ORIGINAL = 'original'; // enum original values (value / name)

    public const FIELDS_SWEET_BASIC = 'sweet-basic'; // only id / title

    public const FIELDS_SWEET_WITH_STATUS = 'sweet-with-status'; // only id / title / isOn

    public const FIELDS_SWEET_FULL = 'sweet-full'; // All fields including custom and computed
}
