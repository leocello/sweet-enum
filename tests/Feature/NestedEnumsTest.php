<?php

use Leocello\SweetEnum\Examples\Status\Status;

describe('Nested enums', function () {
    it('can get data from nested enum', function () {
        $activeStatus = Status::Active;
        $inactiveStatus = Status::Inactive;

        expect($activeStatus->color()->hex())->toBe('#00FF00')
            ->and($inactiveStatus->color()->rgb())->toBe([255, 0, 0]);
    })->only();
});
