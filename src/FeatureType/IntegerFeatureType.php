<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\IntegerConfigurationType;
use App\Form\Feature\IntegerFeatureFormType;

class IntegerFeatureType implements FeatureTypeInterface
{

    public function getName(): string
    {
        return 'integer';
    }

    public function getStorageType(): string
    {
        return 'integer';
    }

    public function getConfigurationFormClass(): string
    {
        return IntegerConfigurationType::class;
    }

    public function getFormClass(): string
    {
        return IntegerFeatureFormType::class;
    }

    public function getChildren(): array
    {
        return [];
    }
}