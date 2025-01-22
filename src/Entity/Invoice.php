<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    #[ORM\Column(length: 100)]
    private ?string $company_street = null;

    #[ORM\Column(length: 10)]
    private ?string $company_street_number = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $company_street_flat_number = null;

    #[ORM\Column(length: 100)]
    private ?string $company_city = null;

    #[ORM\Column(length: 10)]
    private ?string $company_post_code = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column(length: 35, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 16)]
    private ?string $tax_number = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyStreet(): ?string
    {
        return $this->company_street;
    }

    public function setCompanyStreet(string $company_street): static
    {
        $this->company_street = $company_street;

        return $this;
    }

    public function getCompanyStreetNumber(): ?string
    {
        return $this->company_street_number;
    }

    public function setCompanyStreetNumber(string $company_street_number): static
    {
        $this->company_street_number = $company_street_number;

        return $this;
    }

    public function getCompanyStreetFlatNumber(): ?string
    {
        return $this->company_street_flat_number;
    }

    public function setCompanyStreetFlatNumber(?string $company_street_flat_number): static
    {
        $this->company_street_flat_number = $company_street_flat_number;

        return $this;
    }

    public function getCompanyCity(): ?string
    {
        return $this->company_city;
    }

    public function setCompanyCity(string $company_city): static
    {
        $this->company_city = $company_city;

        return $this;
    }

    public function getCompanyPostCode(): ?string
    {
        return $this->company_post_code;
    }

    public function setCompanyPostCode(string $company_post_code): static
    {
        $this->company_post_code = $company_post_code;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): static
    {
        $this->updated = $updated;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->tax_number;
    }

    public function setTaxNumber(string $tax_number): static
    {
        $this->tax_number = $tax_number;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'company_name' => $this->getCompanyName(),
            'company_street' => $this->getCompanyStreet(),
            'company_street_number' => $this->getCompanyStreetNumber(),
            'company_street_flat_number' => $this->getCompanyStreetFlatNumber(),
            'company_city' => $this->getCompanyCity(),
            'company_post_code' => $this->getCompanyPostCode(),
            'created' => $this->getCreated(),
            'updated' => $this->getUpdated(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'tax_number' => $this->getTaxNumber(),
            'user_id' => $this->getUserId(),
        ];
    }
}
