<?php

use Leocello\SweetEnum\Examples\Color\Color;

describe('Invalid properties', function () {
    test('Invalid property names return null by default', function () {
        expect(Color::Blue->invalidProperty())->toBeNull();
    });

    test('An exception can be thrown if parameter is passed', function () {
        Color::Blue->invalidProperty(strict: true);
    })->throws(InvalidArgumentException::class);

    test('A default value can be passed to a property to be returned in case it is not valid', function () {
        expect(Color::Blue->invalidProperty('default value!'))->toBe('default value!')
            ->and(Color::Yellow->invalidProperty(default: 'default value 2!'))->toBe('default value 2!');
    });

    test('A null value for a property is a valid value', function () {
        expect(Color::White->test(throwsException: true))->toBeNull()
            ->and(Color::White->test(throwsException: true))->not()->toBeString()
            ->and(Color::White->test(throwsException: true))->not()->toBe('')
            ->and(Color::White->test(throwsException: true))->toBeEmpty();
    });

    test('A blank value for a property is a valid value', function () {
        expect(Color::Black->test(throwsException: true))->toBe('')
            ->and(Color::Black->test(throwsException: true))->toBeEmpty()
            ->and(Color::Black->test(throwsException: true))->not()->toBeNull()
            ->and(Color::Black->test(throwsException: true))->toBeString();
    });
});
