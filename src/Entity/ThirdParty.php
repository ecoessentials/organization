<?php

namespace App\Entity;

use App\Repository\ThirdPartyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ThirdPartyRepository::class)
 */
class ThirdParty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $website;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $customer = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $supplier = false;

    /**
     * @ORM\Embedded(class=PostalAddress::class)
     */
    private ?PostalAddress $postalAddress;

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function isCustomer(): bool
    {
        return $this->customer;
    }

    public function setCustomer(bool $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function isSupplier(): bool
    {
        return $this->supplier;

    }

    public function setSupplier(bool $supplier): self
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getPostalAddress(): ?PostalAddress
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(?PostalAddress $postalAddress): self
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }


}
