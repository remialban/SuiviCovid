<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nameStreetAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalAddress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $secondAdditionalAddress;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $municipality;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVaccinated;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idTypeSense;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getNameStreetAddress(): ?string
    {
        return $this->nameStreetAddress;
    }

    public function setNameStreetAddress(?string $nameStreetAddress): self
    {
        $this->nameStreetAddress = $nameStreetAddress;

        return $this;
    }

    public function getAdditionalAddress(): ?string
    {
        return $this->additionalAddress;
    }

    public function setAdditionalAddress(?string $additionalAddress): self
    {
        $this->additionalAddress = $additionalAddress;

        return $this;
    }

    public function getSecondAdditionalAddress(): ?string
    {
        return $this->secondAdditionalAddress;
    }

    public function setSecondAdditionalAddress(?string $secondAdditionalAddress): self
    {
        $this->secondAdditionalAddress = $secondAdditionalAddress;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(?int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function setMunicipality(?string $municipality): self
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getIsVaccinated(): ?bool
    {
        return $this->isVaccinated;
    }

    public function setIsVaccinated(bool $isVaccinated): self
    {
        $this->isVaccinated = $isVaccinated;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getIdTypeSense(): ?int
    {
        return $this->idTypeSense;
    }

    public function setIdTypeSense(?int $idTypeSense): self
    {
        $this->idTypeSense = $idTypeSense;

        return $this;
    }
}
