<?php

namespace Leocello\SweetEnum\Tests\Enums;

use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

enum Color : string implements SweetEnumContract
{
    use SweetEnum;

    const DEFAULT = Color::White;

    #[SweetCase(
        custom: [
            'hex' => '#FFFFFF',
        ],
    )]
    case White = 'white';

    #[SweetCase(
        custom: [
            'hex' => '#000000',
        ],
    )]
    case Black = 'black';

    #[SweetCase(
        title: 'Red color',
        custom: [
            'hex' => '#FF0000',
        ],
    )]
    case Red = 'red';

    #[SweetCase(
        title: 'Green color',
        custom: [
            'hex' => '#00FF00',
        ],
    )]
    case Green = 'green';

    #[SweetCase(
        title: 'Blue color',
        custom: [
            'hex' => '#0000FF',
        ],
    )]
    case Blue = 'blue';

    #[SweetCase(
        title: 'Yellow color',
        isOn: false,
        custom: [
            'hex' => '#FFFF00',
        ],
    )]
    case Yellow = 'yellow';
}
