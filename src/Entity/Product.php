<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=ProductFeatureGroup::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @var Collection<int, ProductFeatureGroup>|iterable<ProductFeatureGroup>
     */
    private Collection|iterable $featureGroups;

    public function __construct()
    {
        $this->featureGroups = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ProductFeatureGroup>|iterable<ProductFeatureGroup>
     */
    public function getFeatureGroups(): Collection|iterable
    {
        return $this->featureGroups;
    }

    public function addFeatureGroup(ProductFeatureGroup $featureGroup): self
    {
        if (!$this->featureGroups->contains($featureGroup)) {
            $this->featureGroups[] = $featureGroup;
            $featureGroup->setProduct($this);
        }

        return $this;
    }

    public function removeFeatureGroup(ProductFeatureGroup $featureGroup): self
    {
        if ($this->featureGroups->removeElement($featureGroup)) {
            // set the owning side to null (unless already changed)
            if ($featureGroup->getProduct() === $this) {
                $featureGroup->setProduct(null);
            }
        }

        return $this;
    }

}
