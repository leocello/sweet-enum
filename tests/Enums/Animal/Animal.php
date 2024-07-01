<?php

namespace Leocello\SweetEnum\Tests\Enums\Animal;

use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method string hex()
 * @method array rgb()
 * @mixin AnimalCaseClass
 */
enum Animal: string implements SweetEnumContract
{
    use SweetEnum;

    public const DEFAULT_CASE_CLASS = AnimalCaseClass::class;

    #[SweetCase(
        title: 'Dog',
        caseClass: AnimalDogCaseClass::class,
    )]
    case Dog = 'dog';

    #[SweetCase(
        title: 'Cat',
        caseClass: AnimalCatCaseClass::class,
    )]
    case Cat = 'cat';

    #[SweetCase(
        title: 'Mouse',
        caseClass: AnimalMouseCaseClass::class,
    )]
    case Mouse = 'mouse';

    #[SweetCase(
        title: 'Rat',
        isOn: false,
        caseClass: AnimalRatCaseClass::class,
    )]
    case Rat = 'rat';

    #[SweetCase(
        title: 'Sheep',
    )]
    case Sheep = 'sheep';
}