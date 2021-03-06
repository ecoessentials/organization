<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\BooleanConfigurationType;

class BooleanFeatureType implements FeatureTypeInterface
{

    public function getName(): string
    {
        return 'boolean';
    }

    public function getStorageType(): string
    {
        return 'boolean';
    }

    public function getConfigurationFormClass(): string
    {
        return BooleanConfigurationType::class;
    }

    public function getFormClass(): string
    {
        // TODO: Implement getFormClass() method.
    }

    public function getChildren(): array
    {
        return [];
    }

}