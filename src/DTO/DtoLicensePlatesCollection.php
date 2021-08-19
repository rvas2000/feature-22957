<?php

namespace App\DTO;

class DtoLicensePlatesCollection extends \ArrayObject
{
    public function append($value): void
    {
        if (get_class($value) !== DtoLicensePlate::class) {
            throw new \TypeError();
        }
        parent::append($value);
    }
}