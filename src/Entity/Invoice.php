<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
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
}
