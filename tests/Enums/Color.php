<?php

namespace Leocello\SweetEnum\Tests\Enums;

use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method string hex()
 * @method array rgb()
 */
enum Color: string implements SweetEnumContract
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

    protected function getComputedFields(): array
    {
        return [
            'rgb' => $this->getRgb(),
        ];
    }

    protected function getRgb(): array
    {
        return sscanf($this->hex(), '#%02x%02x%02x');
    }

    public function cmyk(): array
    {
        [$r, $g, $b] = $this->getRgb();

        $c = (255 - $r) / 255.0 * 100;
        $m = (255 - $g) / 255.0 * 100;
        $y = (255 - $b) / 255.0 * 100;
        $k = min([$c, $m, $y]);

        $c = $c - $k;
        $m = $m - $k;
        $y = $y - $k;

        return [$c, $m, $y, $k];
    }
}
