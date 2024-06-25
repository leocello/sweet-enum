<?php

namespace Leocello\SweetEnum\Tests\Enums\Animal;

class AnimalCatCaseClass extends AnimalCaseClass
{
    public function meow(): string
    {
        return 'Meow!';
    }

    public function doesEat(Animal $animal): bool
    {
        return $animal->is([
            Animal::Mouse,
            Animal::Rat,
        ]);
    }
}
