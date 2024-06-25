<?php

namespace Leocello\SweetEnum\Tests\Enums;

use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method string hex()
 * @method array rgb()
 */
enum Animal: string implements SweetEnumContract
{
    use SweetEnum;

    #[SweetCase(
        title: 'Dog',
    )]
    case Dog = 'dog';

    #[SweetCase(
        title: 'Cat',
    )]
    case Cat = 'cat';

    #[SweetCase(
        title: 'Mouse',
    )]
    case Mouse = 'mouse';
}
