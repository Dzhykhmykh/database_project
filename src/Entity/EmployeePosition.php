<?php

namespace App\Entity;

use App\Repository\EmployeePositionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeePositionRepository::class)]
class EmployeePosition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'employeePositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Employee $employee = null;

    #[ORM\ManyToOne(inversedBy: 'employeePositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobTitle $jobTitle = null;

    #[ORM\ManyToOne(inversedBy: 'employeePositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\ManyToOne(inversedBy: 'employeePositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkingStatus $workingStatus = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFrom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateTo = null;

    #[ORM\Column]
    private ?int $salary = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getJobTitle(): ?JobTitle
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?JobTitle $jobTitle): static
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getWorkingStatus(): ?WorkingStatus
    {
        return $this->workingStatus;
    }

    public function setWorkingStatus(?WorkingStatus $workingStatus): static
    {
        $this->workingStatus = $workingStatus;

        return $this;
    }

    public function getDateFrom(): ?\DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function getDateFromFormatted(): ?string
    {
        return $this->getDateFrom()->format('d.m.Y');
    }

    public function setDateFrom(?\DateTimeInterface $dateFrom): static
    {
        $this->dateFrom = $dateFrom;

        return $this;
    }

    public function getDateTo(): ?\DateTimeInterface
    {
        return $this->dateTo;
    }

    public function getDateToFormatted(): ?string
    {
        if ($this->getDateTo() instanceof \DateTimeInterface) {
            return $this->getDateTo()->format('d.m.Y');
        }
        return null;
    }

    public function setDateTo(?\DateTimeInterface $dateTo): static
    {
        $this->dateTo = $dateTo;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }
}
