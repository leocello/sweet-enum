<?php

namespace Leocello\SweetEnum;

abstract class SweetCaseClass
{
    public function __construct(
        protected readonly SweetEnumContract $case
    ) {
        ///
    }
}
