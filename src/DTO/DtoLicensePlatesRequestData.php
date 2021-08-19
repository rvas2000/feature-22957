<?php

namespace App\DTO;

use App\Exception\ApiException;

class DtoLicensePlatesRequestData
{
    public DtoLicensePlatesCollection $data;

//    public array $data;

    public function __construct(string $jsonData)
    {
        $this->data = new DtoLicensePlatesCollection();
//        $this->data = [];
        $obj = json_decode($jsonData, false, 512, JSON_THROW_ON_ERROR);

        if (!property_exists($obj, 'data')) {
            throw new ApiException();
        }

        if (!is_array($obj->data)) {
            throw new ApiException();
        }

        $numb = 0;
        foreach ($obj->data as $item) {
            $numb++;
            if (!(
                property_exists($item, 'license_plate')
                && property_exists($item, 'from')
                && property_exists($item, 'to')
            )) {
                throw new ApiException(sprintf('Rec. %s: %s', $numb, ApiException::MESSAGE_FORMAT_ERROR));
            }

            $licensePlate = trim($item->license_plate);
            $from         = trim($item->from);
            $to           = trim($item->to);

            if (empty($licensePlate)) {
                throw new ApiException(sprintf('Rec. %s: %s: %s', $numb, 'licensePlate', ApiException::MESSAGE_VALUE_NOT_EMPTY));
            }

            if (empty($from)) {
                throw new ApiException(sprintf('Rec. %s: %s: %s', $numb, 'from', ApiException::MESSAGE_VALUE_NOT_EMPTY));
            }

            if (empty($to)) {
                throw new ApiException(sprintf('Rec. %s: %s: %s', $numb, 'to', ApiException::MESSAGE_VALUE_NOT_EMPTY));
            }

            $licensePlateItem               = new DtoLicensePlate();
            $licensePlateItem->licensePlate = $licensePlate;
            $licensePlateItem->from         = new \DateTime($from);
            $licensePlateItem->to           = new \DateTime($to);

            $this->data[] = $licensePlateItem;
        }
    }
}