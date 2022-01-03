<?php

namespace App\FeatureType;

use App\Form\Feature\Configuration\SelectConfigurationType;
use App\Form\Feature\SelectFeatureFormType;

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

    public function getFormClass(): string
    {
        return SelectFeatureFormType::class;
    }

    public function getChildren(): array
    {
        return [];
    }
}