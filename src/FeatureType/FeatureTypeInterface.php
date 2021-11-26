<?php

namespace App\FeatureType;

use App\FeatureType\Configuration\FeatureConfigurationInterface;

interface FeatureTypeInterface
{
    public function getName(): string;

    public function getStorageType(): string;

    public function getConfigurationFormClass(): string;
}