<?php

namespace App\Entity;

use App\Repository\ProductFeatureGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProductFeatureGroupRepository::class)
 */
class ProductFeatureGroup
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
     * @ORM\OneToMany(targetEntity=ProductFeature::class, mappedBy="group", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @var Collection<int, ProductFeature>|iterable<ProductFeature>
     */
    private Collection|iterable $features;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="fetureGroups")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup()
     */
    private ?Product $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition()
     */
    private ?int $position = -1;

    public function __construct()
    {
        $this->features = new ArrayCollection();
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
     * @return Collection<int, ProductFeature>|iterable<ProductFeature>
     */
    public function getFeatures(): Collection|iterable
    {
        return $this->features;
    }

    public function addFeature(ProductFeature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
            $feature->setGroup($this);
        }

        return $this;
    }

    public function removeFeature(ProductFeature $feature): self
    {
        if ($this->features->removeElement($feature)) {
            // set the owning side to null (unless already changed)
            if ($feature->getGroup() === $this) {
                $feature->setGroup(null);
            }
        }

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}
