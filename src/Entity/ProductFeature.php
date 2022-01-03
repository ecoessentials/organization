<?php

namespace App\Entity;

use App\Repository\ProductFeatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductFeatureRepository::class)
 */
class ProductFeature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Feature::class)
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private Feature $feature;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity=ProductFeatureGroup::class, inversedBy="features")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup()
     */
    private ?ProductFeatureGroup $group;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition()
     */
    private int $position = -1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(?Feature $feature): self
    {
        $this->feature = $feature;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGroup(): ?ProductFeatureGroup
    {
        return $this->group;
    }

    public function setGroup(?ProductFeatureGroup $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }


}
