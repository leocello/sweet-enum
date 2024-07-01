<?php

use Leocello\SweetEnum\Tests\Enums\Animal\Animal;

describe('Access options', function () {
    it('can get the case related class name', function () {
        expect(Animal::Dog->getClassName())->toBe(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalDogCaseClass::class)
            ->and(Animal::Cat->getClassName())->toBe(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalCatCaseClass::class)
            ->and(Animal::Mouse->getClassName())->toBe(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalMouseCaseClass::class)
            ->and(Animal::Rat->getClassName())->toBe(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalRatCaseClass::class);
    });

    it('can get the case related class instance', function () {
        expect(Animal::Dog->getClassInstance())->toBeInstanceOf(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalDogCaseClass::class)
            ->and(Animal::Cat->getClassInstance())->toBeInstanceOf(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalCatCaseClass::class)
            ->and(Animal::Mouse->getClassInstance())->toBeInstanceOf(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalMouseCaseClass::class)
            ->and(Animal::Rat->getClassInstance())->toBeInstanceOf(\Leocello\SweetEnum\Tests\Enums\Animal\AnimalRatCaseClass::class);
    });

    it('can access case classes functionality directly from enum case', function () {
        expect(Animal::Dog->doesEat(Animal::Cat))->toBeTrue()
            ->and(Animal::Cat->doesEat(Animal::Mouse))->toBeTrue()
            ->and(Animal::Mouse->doesEat(Animal::Dog))->toBeFalse()
            ->and(Animal::Dog->bark())->toBe('Woof!')
            ->and(Animal::Dog->meow())->toBe('Sorry, a dog cannot meow')
            ->and(Animal::Dog->squeak())->toBe('Sorry, a dog cannot squeak')
            ->and(Animal::Cat->meow())->toBe('Meow!');
    });

    it('can access base case class functionality if no specific class is assigned to case', function () {
        expect(Animal::Sheep->hasClass())->toBeTrue()
            ->and(Animal::Sheep->bark())->toBe('Sorry, a sheep cannot bark');
    });
});