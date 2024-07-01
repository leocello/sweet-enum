<?php

use Leocello\SweetEnum\Examples\Animal\Animal;
use Leocello\SweetEnum\Examples\Color\Color;

describe('Access options', function () {
    it('can get default case', function () {
        $defaultColor = Color::getDefaultCase();

        expect($defaultColor)->toBe(Color::White);
    });

    test('if no default case is set, than it can get first case as default', function () {
        $defaultAnimal = Animal::getDefaultCase();

        expect($defaultAnimal)->toBe(Animal::Dog);
    });

    it('can get a random enum case', function () {
        $randomAnimal = Animal::getRandomCase();

        expect($randomAnimal)->toBeInstanceOf(Animal::class);
    });
});
