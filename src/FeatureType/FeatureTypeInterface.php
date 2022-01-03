<?php

namespace App\FeatureType;

interface FeatureTypeInterface
{
    public function getName(): string;

    public function getStorageType(): string;

    public function getConfigurationFormClass(): string;

    public function getFormClass(): string;

    public function getChildren(): array;
}