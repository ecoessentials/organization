<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\Dim2ConfigurationType;
use App\Form\Feature\Dim2FeatureFormType;

class Dim2FeatureType implements FeatureTypeInterface
{

    public function getName(): string
    {
        return 'dim2';
    }

    public function getStorageType(): string
    {
        return 'compound';
    }

    public function getChildren(): array
    {
        return [
            'x' => 'integer',
            'y' => 'integer'
        ];
    }

    public function getConfigurationFormClass(): string
    {
        return Dim2ConfigurationType::class;
    }

    public function getFormClass(): string
    {
        return Dim2FeatureFormType::class;
    }

    public function decodeUnit(int $unitAsInteger)
    {
        return $this->getUnits()[$unitAsInteger];
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
}