<?php

namespace Leocello\SweetEnum;

enum SweetFields
{
    case Original; // enum original values (value / name)
    case Basic; // only id / title
    case BasicWithStatus; // only id / title / isOn
    case Sweet; // All custom and computed fields - but without status or the original (value / name)
    case Full; // All fields including original, custom and computed
}
