<?php

namespace App\Entity;

use App\Repository\ProjectItemModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectItemModelRepository::class)
 */
class ProjectItemModel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $count = 1;

    /**
     * @ORM\Column(type="simple_array")
     *
     * @Assert\Count(min=1)
     * @Assert\All({
     *     @Assert\Type(type="integer"),
     *     @Assert\NotEqualTo(value=0)
     * })
     */
    private $quantities = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectItem::class, inversedBy="models")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectItem;

    /**
     * @ORM\OneToMany(targetEntity=FeatureValue::class, mappedBy="model", cascade={"persist"})
     */
    private $featureValues;

    public function __construct()
    {
        $this->featureValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getQuantities(): ?array
    {
        return $this->quantities;
    }

    public function setQuantities(array $quantities): self
    {
        $this->quantities = $quantities;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

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
     * @return Collection|FeatureValue[]
     */
    public function getFeatureValues(): Collection
    {
        return $this->featureValues;
    }

    public function addFeatureValue(FeatureValue $featureValue): self
    {
        if (!$this->featureValues->contains($featureValue)) {
            $this->featureValues[] = $featureValue;
            $featureValue->setModel($this);
        }

        return $this;
    }

    public function removeFeatureValue(FeatureValue $featureValue): self
    {
        if ($this->featureValues->removeElement($featureValue)) {
            // set the owning side to null (unless already changed)
            if ($featureValue->getModel() === $this) {
                $featureValue->setModel(null);
            }
        }

        return $this;
    }
}
