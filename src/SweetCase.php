<?php

namespace Leocello\SweetEnum;

#[\Attribute]
class SweetCase
{
    public function __construct(
        public readonly string|null $title = null,
        public readonly bool $isOn = true,
        public readonly array $custom = [],
        public readonly string|null $caseClass = null,
    )
    {
        ///
    }
}
