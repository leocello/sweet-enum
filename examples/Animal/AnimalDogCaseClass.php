<?php

namespace Leocello\SweetEnum\Examples\Animal;


class AnimalDogCaseClass extends AnimalCaseClass
{
    public function bark(): string
    {
        return 'Woof!';
    }

    public function doesEat(Animal $animal): bool
    {
        return $animal->is(Animal::Cat);
    }
}
