<?php

namespace Leocello\SweetEnum\Examples\Animal;

use Leocello\SweetEnum\SweetCase;
use Leocello\SweetEnum\SweetEnum;
use Leocello\SweetEnum\SweetEnumContract;

/**
 * @method string hex()
 * @method array rgb()
 *
 * @mixin AnimalCaseClass
 */
enum Animal: string implements SweetEnumContract
{
    use SweetEnum;

    public const DEFAULT_CASE_CLASS = AnimalCaseClass::class;

    #[SweetCase(
        caseClass: AnimalDogCaseClass::class,
        title: 'Dog',
    )]
    case Dog = 'dog';

    #[SweetCase(
        caseClass: AnimalCatCaseClass::class,
        title: 'Cat',
    )]
    case Cat = 'cat';

    #[SweetCase(
        caseClass: AnimalMouseCaseClass::class,
        title: 'Mouse',
    )]
    case Mouse = 'mouse';

    #[SweetCase(
        caseClass: AnimalRatCaseClass::class,
        title: 'Rat',
        isOn: false,
    )]
    case Rat = 'rat';

    #[SweetCase(
        title: 'Sheep',
    )]
    case Sheep = 'sheep';

    protected function getComputedFields(): array
    {
        return [
            'bark' => $this->bark(),
            'meow' => $this->meow(),
            'squeak' => $this->squeak(),
        ];
    }
}
