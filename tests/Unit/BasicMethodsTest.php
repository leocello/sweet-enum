<?php

use Leocello\SweetEnum\Examples\Color\Color;
use Leocello\SweetEnum\Examples\Status\Status;

describe('Basic methods', function () {
    test('method is() returns correctly for single cases', function () {
        expect(Color::Red->is(Color::Blue))->toBeFalse()
            ->and(Color::Red->is(Color::Red))->toBeTrue();
    });

    test('method is() returns correctly for list of cases', function () {
        expect(Color::Red->is([Color::Blue, Color::Black]))->toBeFalse()
            ->and(Color::Red->is([Color::Red, Color::Yellow]))->toBeTrue();
    });

    test('method is() only accepts enum cases from the same enum type', function () {
        Color::Red->is(Status::Active);
    })->throws(InvalidArgumentException::class);

    test('method is() only accepts enum cases from the same enum type when passing a list of cases', function () {
        Color::Red->is([Color::Red, Status::Active]);
    })->throws(InvalidArgumentException::class);
});