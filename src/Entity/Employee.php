<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $secondName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $patronymic = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkingStatus $workingStatus = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JobTitle $JobTitle = null;

    #[ORM\ManyToOne(inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(string $secondName): static
    {
        $this->secondName = $secondName;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): static
    {
        $this->patronymic = $patronymic;

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

    public function getJobTitle(): ?JobTitle
    {
        return $this->JobTitle;
    }

    public function setJobTitle(?JobTitle $JobTitle): static
    {
        $this->JobTitle = $JobTitle;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }
}
