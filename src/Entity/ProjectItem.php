<?php

namespace App\Entity;

use App\Repository\ProjectItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectItemRepository::class)
 */
class ProjectItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity=Project::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Gedmo\SortableGroup()
     */
    private ?Project $project = null;

    /**
     * @ORM\OneToMany(targetEntity=ProjectItemSupplierEstimate::class, mappedBy="projectItem", orphanRemoval=true, cascade={"persist", "remove"})
     *
     * @var Collection<int, ProjectItemSupplierEstimate>
     */
    private Collection|iterable $supplierEstimates;

    /**
     * @ORM\OneToOne(targetEntity=ProjectItemSupplierEstimate::class, cascade={"persist", "remove"})
     */
    private ProjectItemSupplierEstimate $chosenSupplierEstimate;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @Gedmo\SortablePosition()
     */
    private int $position = -1;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $note;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $customerNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $supplierNote;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $internalNote;

    /**
     * @ORM\OneToMany(targetEntity=ProjectItemProduct::class, mappedBy="projectItem", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position"="ASC"})
     *
     * @Assert\Valid()
     *
     * @var Collection<int, ProjectItemProduct>
     */
    private Collection $products;

    /**
     * @ORM\OneToMany(targetEntity=ProjectItemModel::class, mappedBy="projectItem", cascade={"persist"})
     *
     * @Assert\Valid()
     *
     * @var Collection<int, ProjectItemModel>
     */
    private Collection $models;

    public function __construct()
    {
        $this->supplierEstimates = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->models = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, ProjectItemSupplierEstimate>
     */
    public function getSupplierEstimates(): Collection
    {
        return $this->supplierEstimates;
    }

    public function addSupplierEstimate(ProjectItemSupplierEstimate $supplierEstimate): self
    {
        if (!$this->supplierEstimates->contains($supplierEstimate)) {
            $this->supplierEstimates[] = $supplierEstimate;
            $supplierEstimate->setProjectItem($this);
        }

        return $this;
    }

    public function removeSupplierEstimate(ProjectItemSupplierEstimate $supplierEstimate): self
    {
        if ($this->supplierEstimates->removeElement($supplierEstimate)) {
            // set the owning side to null (unless already changed)
            if ($supplierEstimate->getProjectItem() === $this) {
                $supplierEstimate->setProjectItem(null);
            }
        }

        return $this;
    }

    public function getChosenSupplierEstimate(): ?ProjectItemSupplierEstimate
    {
        return $this->chosenSupplierEstimate;
    }

    public function setChosenSupplierEstimate(?ProjectItemSupplierEstimate $chosenSupplierEstimate): self
    {
        $this->chosenSupplierEstimate = $chosenSupplierEstimate;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCustomerNote(): ?string
    {
        return $this->customerNote;
    }

    public function setCustomerNote(?string $customerNote): self
    {
        $this->customerNote = $customerNote;

        return $this;
    }

    public function getSupplierNote(): ?string
    {
        return $this->supplierNote;
    }

    public function setSupplierNote(?string $supplierNote): self
    {
        $this->supplierNote = $supplierNote;

        return $this;
    }

    public function getInternalNote(): ?string
    {
        return $this->internalNote;
    }

    public function setInternalNote(?string $internalNote): self
    {
        $this->internalNote = $internalNote;

        return $this;
    }

    /**
     * @return Collection|ProjectItemProduct[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProjectItemProduct $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProjectItem($this);
        }

        return $this;
    }

    public function removeProduct(ProjectItemProduct $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProjectItem() === $this) {
                $product->setProjectItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectItemModel[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(ProjectItemModel $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setProjectItem($this);
        }

        return $this;
    }

    public function removeModel(ProjectItemModel $model): self
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getProjectItem() === $this) {
                $model->setProjectItem(null);
            }
        }

        return $this;
    }
}
