<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class PostalAddress
{

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
