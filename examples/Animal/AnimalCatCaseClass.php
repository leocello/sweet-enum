<?php

namespace Leocello\SweetEnum\Examples\Animal;

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
