<?php

namespace App\Entity;

use App\Repository\FeatureValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeatureValueRepository::class)
 */
class FeatureValue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var ProductFeature
     *
     * @ORM\ManyToOne(targetEntity=ProductFeature::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ProductFeature $productFeature = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $integerValue = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $booleanValue = null;

    /**
     * @var ProjectItemProduct
     *
     * @ORM\ManyToOne(targetEntity=ProjectItemProduct::class, inversedBy="featureValues")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?ProjectItemProduct $projectItemProduct = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $storageType = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $jsonValue = [];

    /**
     * @ORM\ManyToOne(targetEntity=FeatureValue::class, inversedBy="children")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $featureValue;

    /**
     * @ORM\OneToMany(targetEntity=FeatureValue::class, mappedBy="featureValue", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @var Collection<int, FeatureValue>
     */
    private Collection $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $childName;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})  
     */
    private ?bool $modelSpecific = false;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectItemModel::class, inversedBy="featureValues")
     */
    private $model;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function __clone(): void
    {
        $this->id = null;
        foreach ($this->children as &$child) {
            $child = clone $child;
            $child->setFeatureValue($this);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductFeature(): ?ProductFeature
    {
        return $this->productFeature;
    }

    public function setProductFeature(?ProductFeature $productFeature): self
    {
        $this->productFeature = $productFeature;

        return $this;
    }

    public function getIntegerValue(): ?int
    {
        return $this->integerValue;
    }

    public function setIntegerValue(?int $integerValue): self
    {
        $this->integerValue = $integerValue;

        return $this;
    }

    public function getBooleanValue(): ?bool
    {
        return $this->booleanValue;
    }

    public function setBooleanValue(?bool $booleanValue): self
    {
        $this->booleanValue = $booleanValue;

        return $this;
    }

    public function getProjectItemProduct(): ?ProjectItemProduct
    {
        return $this->projectItemProduct;
    }

    public function setProjectItemProduct(?ProjectItemProduct $projectItemProduct): self
    {
        $this->projectItemProduct = $projectItemProduct;

        return $this;
    }

    public function getStorageType(): ?string
    {
        return $this->storageType;
    }

    public function setStorageType(?string $storageType): self
    {
        $this->storageType = $storageType;

        return $this;
    }

    public function getValue(): mixed
    {
        $method = 'get' . ucfirst($this->getStorageType()) . 'Value';
        return $this->$method();
    }

    public function setValue($value)
    {
        $method = 'set' . ucfirst($this->getStorageType()) . 'Value';
        $this->$method($value);
    }

    public function getJsonValue(): ?array
    {
        return $this->jsonValue;
    }

    public function setJsonValue(?array $jsonValue): self
    {
        $this->jsonValue = $jsonValue;

        return $this;
    }

    public function setCompoundValue(?array $compoundValue): self
    {
        foreach ($compoundValue as $name => $value) {
            $this->setChildValue($name, $value);
        }

        return $this;
    }

    private function setChildValue(string $childName, $value) {
        foreach ($this->children as $child) {
            if ($child->getChildName() === $childName) {
                $child->setValue($value);
                return;
            }
        }
    }

    public function getCompoundValue() : array
    {
        $result = [];
        foreach ($this->children as $child) {
            $result[$child->getChildName()] = $child->getValue();
        }
        return $result;
    }

    public function getFeatureValue(): ?self
    {
        return $this->featureValue;
    }

    public function setFeatureValue(?self $featureValue): self
    {
        $this->featureValue = $featureValue;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setFeatureValue($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getFeatureValue() === $this) {
                $child->setFeatureValue(null);
            }
        }

        return $this;
    }

    public function getChildName(): ?string
    {
        return $this->childName;
    }

    public function setChildName(?string $chidName): self
    {
        $this->childName = $chidName;

        return $this;
    }

    public function isModelSpecific(): ?bool
    {
        return $this->modelSpecific;
    }

    public function setModelSpecific(?bool $modelSpecific): self
    {
        if ($modelSpecific === null) {
            $modelSpecific = false;
        }
        $this->modelSpecific = $modelSpecific;

        return $this;
    }

    public function getModel(): ?ProjectItemModel
    {
        return $this->model;
    }

    public function setModel(?ProjectItemModel $model): self
    {
        $this->model = $model;

        return $this;
    }
}
