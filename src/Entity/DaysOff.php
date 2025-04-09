<?php

namespace App\Entity;

use App\Repository\DaysOffRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DaysOffRepository::class)]
class DaysOff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'daysOffs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DaysOffType $daysOffType = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'daysOffs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $dateFrom = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $dateTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDaysOffType(): ?DaysOffType
    {
        return $this->daysOffType;
    }

    public function setDaysOffType(?DaysOffType $daysOffType): static
    {
        $this->daysOffType = $daysOffType;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(?Employee $employee): static
    {
        $this->employee = $employee;

        return $this;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTimeInterface $dateFrom): static
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTimeInterface $dateTo): static
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function getDateFromFormatted(): string
    {
        return $this->dateFrom->format("d.m.Y");
    }

    public function getDateToFormatted(): string
    {
        return $this->dateTo->format("d.m.Y");
    }
}
