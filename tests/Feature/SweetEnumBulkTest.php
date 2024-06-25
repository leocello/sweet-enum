<?php

use Leocello\SweetEnum\Tests\Enums\Color;

describe('SweetEnum bulk', function () {
    it('is possible to run callback in each active option and collect results', function () {
        $string = '';

        Color::foreach(callback: function (Color $color) use (&$string) {
            if (strlen($string) > 0) {
                $string .= ', ';
            }

            $string .= $color->id();
        });

        expect($string)->toBeString()
            ->and($string)->toContain('white', 'black', 'red', 'green', 'blue')
            ->and($string)->not()->toContain('yellow');
    });

    it('is possible to run callback in each option (include inactive) and collect results', function () {
        $string = '';

        Color::foreach(callback: function (Color $color) use (&$string) {
            if (strlen($string) > 0) {
                $string .= ', ';
            }

            $string .= $color->id();
        }, onlyActives: false);

        expect($string)->toBeString()
            ->and($string)->toContain('white', 'black', 'red', 'green', 'blue')
            ->and($string)->toContain('yellow');
    });

    it('is possible to map based on callback in each active option and collect results', function () {
        $results = Color::map(callback: function (Color $color) {
            return 'it\'s a '.strtolower($color->title());
        });

        expect($results)->toBeArray()
            ->and($results)->toMatchArray([
                'blue' => 'it\'s a blue color',
                'green' => 'it\'s a green color',
            ])
            ->and($results)->not()->toHaveKeys([
                'yellow',
            ]);
    });

    it('is possible to map based on callback in each option (include inactive) and collect results', function () {
        $results = Color::map(callback: function (Color $color) {
            return 'it\'s a '.strtolower($color->title());
        }, onlyActives: false);

        expect($results)->toBeArray()
            ->and($results)->toMatchArray([
                'blue' => 'it\'s a blue color',
                'green' => 'it\'s a green color',
                'yellow' => 'it\'s a yellow color',
            ]);
    });

    it('is possible to return all active cases info as array', function () {
        $info = Color::getCasesInfo(fields: ['id', 'title', 'hex', 'rgb']);

        expect($info)->toBeArray()
            ->and($info)->toHaveKeys([
                'blue',
                'green',
                'white',
            ])
            ->and($info)->not()->toHaveKeys([
                'yellow',
            ])
            ->and($info['blue'])->toMatchArray([
                'id' => 'blue',
                'title' => 'Blue color',
                'hex' => '#0000FF',
                'rgb' => [0, 0, 255],
            ]);
    });

    it('is possible to return all cases (including inactive) info as array', function () {
        $info = Color::getCasesInfo(fields: ['id', 'title', 'hex', 'rgb'], onlyActives: false);

        expect($info)->toBeArray()
            ->and($info)->toHaveKeys([
                'yellow',
            ])
            ->and($info['yellow'])->toMatchArray([
                'id' => 'yellow',
                'title' => 'Yellow color',
                'hex' => '#FFFF00',
                'rgb' => [255, 255, 0],
            ]);
    });

    /// TODO:
    ///  - filter ?
    ///  - find ?
    ///  - sort ?
    ///  - reduce ?
});
