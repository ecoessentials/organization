<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\IntegerConfigurationType;
use App\Form\Feature\Configuration\LengthConfigurationType;
use App\Form\Feature\IntegerFeatureFormType;
use App\Form\Feature\LengthFeatureFormType;

class LengthFeatureType implements FeatureTypeInterface
{

    public function getName(): string
    {
        return 'length';
    }

    public function getStorageType(): string
    {
        return 'integer';
    }

    public function getConfigurationFormClass(): string
    {
        return LengthConfigurationType::class;
    }

    public function getFormClass(): string
    {
        return LengthFeatureFormType::class;
    }

    public function getUnits()
    {
        return [
            0 => '1/10 mm',
            1 => 'mm',
            2 => 'cm',
            4 => 'm',
        ];
    }

    public function decodeUnit(int $unitAsInteger) {
        return $this->getUnits()[$unitAsInteger];
    }

    public function getChildren(): array
    {
        return [];
    }
}