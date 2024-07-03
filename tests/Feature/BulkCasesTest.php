<?php

use Leocello\SweetEnum\Examples\Color\Color;

describe('Bulk cases', function () {
    it('is possible to run callback in each active option and collect results', function () {
        $string = '';

        Color::foreach(callback: function (Color $color) use (&$string): void {
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

        Color::foreach(callback: function (Color $color) use (&$string): void {
            if (strlen($string) > 0) {
                $string .= ', ';
            }

            $string .= $color->id();
        }, onlyActive: false);

        expect($string)->toBeString()
            ->and($string)->toContain('white', 'black', 'red', 'green', 'blue')
            ->and($string)->toContain('yellow');
    });

    it('is possible to map based on callback in each option and collect results', function () {
        $callback = fn (Color $color): string => 'it\'s a '.strtolower($color->title());

        $onlyActiveResults = Color::map(callback: $callback);
        $allResults = Color::map(callback: $callback, onlyActive: false);

        expect($onlyActiveResults)->toBeArray()
            ->and($onlyActiveResults)->toMatchArray([
                'blue' => 'it\'s a blue color',
                'green' => 'it\'s a green color',
            ])
            ->and($onlyActiveResults)->not()->toHaveKeys([
                'yellow',
            ])
            ->and($allResults)->toBeArray()
            ->and($allResults)->toMatchArray([
                'blue' => 'it\'s a blue color',
                'green' => 'it\'s a green color',
                'yellow' => 'it\'s a yellow color',
            ]);
    });

    it('is possible to reduce based on callback in each option', function () {
        $callback = function (?string $concatenated, Color $color): string {
            if (is_null($concatenated)) {
                $concatenated = '';
            }

            if (strlen($concatenated) > 0) {
                $concatenated .= ', ';
            }

            return $concatenated.$color->name();
        };

        $onlyActiveResults = Color::reduce(callback: $callback);
        $allResults = Color::reduce(callback: $callback, onlyActive: false);

        expect($onlyActiveResults)->toBeString()
            ->and($onlyActiveResults)->toContain('White', 'Black', 'Red', 'Green', 'Blue')
            ->and($onlyActiveResults)->not()->toContain('Yellow')
            ->and($allResults)->toBeString()
            ->and($allResults)->toContain('White', 'Black', 'Red', 'Green', 'Blue')
            ->and($allResults)->toContain('Yellow');
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
        $info = Color::getCasesInfo(fields: ['id', 'title', 'hex', 'rgb'], onlyActive: false);

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

    test('if not added to computed values then values from public methods are not available on info array unless manually prompted', function () {
        expect(Color::getCasesInfo(Color::FIELDS_ORIGINAL)['blue'])->not()->toHaveKeys(['cmyk'])
            ->and(Color::getCasesInfo(Color::FIELDS_SWEET_BASIC)['blue'])->not()->toHaveKeys(['cmyk'])
            ->and(Color::getCasesInfo(Color::FIELDS_SWEET_WITH_STATUS)['blue'])->not()->toHaveKeys(['cmyk'])
            ->and(Color::getCasesInfo(Color::FIELDS_SWEET_FULL)['blue'])->not()->toHaveKeys(['cmyk'])
            ->and(Color::getCasesInfo(['cmyk'])['blue'])->toHaveKeys(['cmyk']);
    });
});
