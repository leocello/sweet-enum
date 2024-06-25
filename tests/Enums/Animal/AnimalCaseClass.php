<?php

namespace Leocello\SweetEnum\Tests\Enums\Animal;

use Leocello\SweetEnum\SweetCaseClass;

class AnimalCaseClass extends SweetCaseClass
{
    public function bark(): string
    {
        return 'Sorry, a '.strtolower($this->case->title()).' cannot bark';
    }

    public function meow(): string
    {
        return 'Sorry, a '.strtolower($this->case->title()).' cannot meow';
    }

    public function squeak(): string
    {
        return 'Sorry, a '.strtolower($this->case->title()).' cannot squeak';
    }

    public function doesEat(Animal $animal): bool
    {
        return false;
    }
}
