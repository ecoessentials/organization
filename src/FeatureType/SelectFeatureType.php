<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\SelectConfigurationType;

class SelectFeatureType implements FeatureTypeInterface
{

    public function getName(): string
    {
        return 'select';
    }

    public function getStorageType(): string
    {
        return 'json';
    }

    public function getConfigurationFormClass(): string
    {
        return SelectConfigurationType::class;
    }
}