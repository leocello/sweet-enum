<?php

use Leocello\SweetEnum\Examples\Color\Color;

describe('Single case', function () {
    it('can be used as normal enums', function () {
        $red = Color::Red;
        $green = Color::Green;

        expect($red->name)->toBe('Red')
            ->and($red->value)->toBe('red')
            ->and($green->name)->toBe('Green')
            ->and($green->value)->toBe('green');
    });

    it('can access properties as methods', function () {
        $blue = Color::Blue;
        $yellow = Color::Yellow;

        expect($blue->id())->toBe('blue')
            ->and($blue->title())->toBe('Blue color')
            ->and($blue->isOn())->toBeTrue()
            ->and($blue->hex())->toBe('#0000FF')
            ->and($yellow->isOn())->toBeFalse();
    });

    it('can access computed properties as methods', function () {
        $blue = Color::Blue;
        $yellow = Color::Yellow;

        expect($blue->id())->toBe('blue')
            ->and($blue->rgb())->toMatchArray([0, 0, 255])
            ->and($yellow->id())->toBe('yellow')
            ->and($yellow->rgb())->toMatchArray([255, 255, 0]);
    });

    it('uses enum name as title if not explicit', function () {
        $black = Color::Black;
        $white = Color::White;

        expect($white->title())->toBe('White')
            ->and($black->name())->toBe('Black');
    });

    it('can return all its basic values as an array', function () {
        expect(Color::Blue->toArray())->toBe([
            'id' => 'blue',
            'title' => 'Blue color',
        ]);
    });

    it('can return all its original values as an array', function () {
        expect(Color::Blue->toArray(Color::FIELDS_ORIGINAL))->toBe([
            'value' => 'blue',
            'name' => 'Blue',
        ]);
    });

    it('can return all its basic values with the status as an array', function () {
        expect(Color::Blue->toArray(Color::FIELDS_BASIC_WITH_STATUS))->toBe([
            'isOn' => true,
            'id' => 'blue',
            'title' => 'Blue color',
        ]);
    });

    it('can return all sweet values (custom and computed) but without status or original values (value and name) as an array', function () {
        expect(Color::Blue->toArray(Color::FIELDS_SWEET))->toBe([
            'id' => 'blue',
            'title' => 'Blue color',
            'hex' => '#0000FF',
            'rgb' => [0, 0, 255],
        ]);
    });

    it('can return all its values (including custom) with the status as an array', function () {
        expect(Color::Blue->toArray(Color::FIELDS_FULL))->toBe([
            'isOn' => true,
            'value' => 'blue',
            'id' => 'blue',
            'name' => 'Blue',
            'title' => 'Blue color',
            'hex' => '#0000FF',
            'rgb' => [0, 0, 255],
        ]);
    });

    it('can return customised values as an array', function () {
        expect(Color::Blue->toArray(['id', 'hex', 'rgb']))->toBe([
            'id' => 'blue',
            'hex' => '#0000FF',
            'rgb' => [0, 0, 255],
        ]);
    });

    it('can return values from public methods as other backed enums', function () {
        expect(Color::Blue->cmyk())->toBe([
            100.0,
            100.0,
            0.0,
            0.0,
        ]);
    });

    test('if not added to computed values then values from public methods are not available on info array unless manually prompted', function () {
        expect(Color::Blue->toArray(Color::FIELDS_ORIGINAL))->not()->toHaveKeys(['cmyk'])
            ->and(Color::Blue->toArray(Color::FIELDS_BASIC))->not()->toHaveKeys(['cmyk'])
            ->and(Color::Blue->toArray(Color::FIELDS_BASIC_WITH_STATUS))->not()->toHaveKeys(['cmyk'])
            ->and(Color::Blue->toArray(Color::FIELDS_SWEET))->not()->toHaveKeys(['cmyk'])
            ->and(Color::Blue->toArray(Color::FIELDS_FULL))->not()->toHaveKeys(['cmyk'])
            ->and(Color::Blue->toArray(['cmyk']))->toHaveKeys(['cmyk']);
    });

    test('at least one field is required if trying to get customised values as an array', function () {
        Color::Blue->toArray([]);
    })->throws(InvalidArgumentException::class);
});
