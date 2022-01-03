<?php

namespace App\Entity;

use App\Repository\ProjectItemProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectItemProductRepository::class)
 */
class ProjectItemProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition()
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectItem::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup()
     *
     */
    private $projectItem;

    /**
     *
     * @ORM\OneToMany(targetEntity=FeatureValue::class, mappedBy="projectItemProduct", orphanRemoval=true, cascade={"persist"})
     *
     * @Assert\Valid()
     *
     * @var Collection<int, FeatureValue>|iterable<Feature>
     */
    private Collection|iterable $featureValues;

    public function __construct()
    {
        $this->featureValues = new ArrayCollection();
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

    public function getProjectItem(): ?ProjectItem
    {
        return $this->projectItem;
    }

    public function setProjectItem(?ProjectItem $projectItem): self
    {
        $this->projectItem = $projectItem;

        return $this;
    }

    /**
     * @return Collection<int, FeatureValue>|iterable<Feature>
     */
    public function getFeatureValues(): Collection|iterable
    {
        return $this->featureValues;
    }

    public function addFeatureValue(FeatureValue $featureValue): self
    {
        if (!$this->featureValues->contains($featureValue)) {
            $this->featureValues[] = $featureValue;
            $featureValue->setProjectItemProduct($this);
        }

        return $this;
    }

    public function removeFeatureValue(FeatureValue $featureValue): self
    {
        if ($this->featureValues->removeElement($featureValue)) {
            // set the owning side to null (unless already changed)
            if ($featureValue->getProjectItemProduct() === $this) {
                $featureValue->setProjectItemProduct(null);
            }
        }

        return $this;
    }

}
