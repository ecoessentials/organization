<?php

namespace App\Entity;

use App\Repository\ProjectItemSupplierEstimateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectItemSupplierEstimateRepository::class)
 */
class ProjectItemSupplierEstimate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=ThirdParty::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ThirdParty $supplier;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectItem::class, inversedBy="supplierEstimates")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ProjectItem $projectItem;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplier(): ?ThirdParty
    {
        return $this->supplier;
    }

    public function setSupplier(?ThirdParty $supplier): self
    {
        $this->supplier = $supplier;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
