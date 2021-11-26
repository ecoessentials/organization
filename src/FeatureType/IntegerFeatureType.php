<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\IntegerConfigurationType;

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
}