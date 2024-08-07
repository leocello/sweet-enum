<?php

namespace Leocello\SweetEnum\Examples\Status;

use Leocello\SweetEnum\Examples\Color\Color;
use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method Color color()
 */
enum Status: string implements SweetEnumContract
{
    use SweetEnum;

    #[SweetCase(
        color: Color::Green,
    )]
    case Active = 'active';

    #[SweetCase(
        color: Color::Red,
    )]
    case Inactive = 'inactive';
}
