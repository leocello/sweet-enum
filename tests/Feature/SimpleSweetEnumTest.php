<?php

use Leocello\SweetEnum\Tests\Cases\Color;

test ('sweet enums can be used as normal enums', function () {
    $red = Color::Red;
    $green = Color::Green;

    expect($red->name)->toBe('Red')
        ->and($red->value)->toBe('red')
        ->and($green->name)->toBe('Green')
        ->and($green->value)->toBe('green')
    ;
});

test ('sweet enum properties can be accessed as methods', function () {
    $blue = Color::Blue;
    $yellow = Color::Yellow;

    expect($blue->id())->toBe('blue')
        ->and($blue->title())->toBe('Blue color')
        ->and($blue->isOn())->toBeTrue()
        ->and($blue->hex())->toBe('#0000FF')
        ->and($yellow->isOn())->toBeFalse()
    ;
});

test ('sweet enum names are used as titles if not explicit', function () {
    $black = Color::Black;
    $white = Color::White;

    expect($white->title())->toBe('White')
        ->and($black->name())->toBe('Black')
    ;
});

test ('a sweet enum case can return all its basic values as an array', function () {
    expect(Color::Blue->toArray())->toBe([
        'id' => 'blue',
        'title' => 'Blue color',
    ]);
})->skip('Still not implemented');

test ('a sweet enum case can return all its original values as an array', function () {
    expect(Color::Blue->toArray(Color::FIELDS_ORIGINAL))->toBe([
        'value' => 'blue',
        'name' => 'Blue',
    ]);
})->skip('Still not implemented');

test ('a sweet enum case can return all its basic values with the status as an array', function () {
    expect(Color::Blue->toArray(Color::FIELDS_SWEET_WITH_STATUS))->toBe([
        'id' => 'blue',
        'title' => 'Blue color',
        'isOn' => true,
    ]);
})->skip('Still not implemented');

test ('a sweet enum case can return all its values (including custom) with the status as an array', function () {
    expect(Color::Blue->toArray(Color::FIELDS_SWEET_FULL))->toBe([
        'id' => 'blue',
        'title' => 'Blue color',
        'isOn' => true,
        'hex' => '#0000FF',
        'value' => 'blue',
        'name' => 'Blue',
    ]);
})->skip('Still not implemented');

test ('a sweet enum case can return customised values as an array', function () {
    expect(Color::Blue->toArray(['id', 'hex']))->toBe([
        'id' => 'blue',
        'hex' => '#0000FF',
    ]);
})->skip('Still not implemented');
