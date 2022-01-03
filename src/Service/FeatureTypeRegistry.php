<?php

namespace App\Service;

use App\FeatureType\FeatureTypeInterface;

class FeatureTypeRegistry
{
    private array $featureTypes = [];

    public function register(FeatureTypeInterface $featureType)
    {
        $this->featureTypes[$featureType->getName()] = $featureType;
    }

    public function get(string $id): FeatureTypeInterface
    {
        return $this->featureTypes[$id];
    }
}