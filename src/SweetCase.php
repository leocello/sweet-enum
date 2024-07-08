<?php

namespace Leocello\SweetEnum;

#[\Attribute]
class SweetCase
{
    public readonly ?string $caseClass;

    public readonly ?string $title;

    public readonly bool $isOn;

    public readonly array $custom;

    public function __construct(
        ?string $caseClass = null,
        ?string $title = null,
        bool $isOn = true,
        ...$custom
    ) {
        $this->caseClass = $caseClass;
        $this->title = $title;
        $this->isOn = $isOn;
        $this->custom = $custom;
    }
}
