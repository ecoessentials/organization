<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 * 
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=ProjectItem::class, mappedBy="project", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position"="ASC"})
     *
     * @var Collection<int, ProjectItem>|iterable<ProjectItem>
     */
    private Collection|iterable $items;

    /**
     * @ORM\ManyToOne(targetEntity=ThirdParty::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private ThirdParty $customer;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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
     * @return Collection<int, ProjectItem>|iterable<ProjectItem>
     */
    public function getItems(): Collection|iterable
    {
        return $this->items;
    }

    public function addItem(ProjectItem $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setProject($this);
        }

        return $this;
    }

    public function removeItem(ProjectItem $item): self
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getProject() === $this) {
                $item->setProject(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?ThirdParty
    {
        return $this->customer;
    }

    public function setCustomer(?ThirdParty $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
