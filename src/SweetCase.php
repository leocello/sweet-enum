<?php

namespace Leocello\SweetEnum;

#[\Attribute]
class SweetCase
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly bool $isOn = true,
        public readonly array $custom = [],
        public readonly ?string $caseClass = null,
    ) {
        ///
    }
}
