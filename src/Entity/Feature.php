<?php

namespace App\Entity;

use App\Repository\FeatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeatureRepository::class)
 */
class Feature
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
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private array $configuration = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $defaultLabel;

    /**
     * @ORM\OneToMany(targetEntity=Option::class, mappedBy="feature", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     *
     * @var Collection<int, Option>
     */
    private Collection $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function setConfiguration(?array $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    public function getDefaultLabel(): ?string
    {
        return $this->defaultLabel ?? $this->getName();
    }

    public function setDefaultLabel(?string $defaultLabel): self
    {
        $this->defaultLabel = $defaultLabel;

        return $this;
    }

    /**
     * @return Collection<int, Option>|iterable<Option>
     */
    public function getOptions(): Collection|iterable
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setFeature($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getFeature() === $this) {
                $option->setFeature(null);
            }
        }

        return $this;
    }

    public function findOptionById(int $id)
    {
        foreach ($this->options as $option) {
            if ($option->getId() === $id) {
                return $option;
            }
        }

        return null;
    }

}
