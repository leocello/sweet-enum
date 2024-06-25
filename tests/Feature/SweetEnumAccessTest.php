<?php

use Leocello\SweetEnum\Tests\Enums\Animal;
use Leocello\SweetEnum\Tests\Enums\Color;

describe('Access options', function () {
    it('can get default case', function () {
        $defaultColor = Color::getDefaultCase();

        expect($defaultColor)->toBe(Color::White);
    });

    test('if no default case is set, than it can get first case as default', function () {
        $defaultAnimal = Animal::getDefaultCase();

        expect($defaultAnimal)->toBe(Animal::Dog);
    });
});
