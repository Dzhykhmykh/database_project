<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $note = null;

    /**
     * @var Collection<int, Contract>
     */
    #[ORM\OneToMany(targetEntity: Contract::class, mappedBy: 'employee')]
    private Collection $contracts;

    /**
     * @var Collection<int, DaysOff>
     */
    #[ORM\OneToMany(targetEntity: DaysOff::class, mappedBy: 'employee')]
    private Collection $daysOffs;

    /**
     * @var Collection<int, EmployeePosition>
     */
    #[ORM\OneToMany(targetEntity: EmployeePosition::class, mappedBy: 'employee')]
    private Collection $employeePositions;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
        $this->daysOffs = new ArrayCollection();
        $this->employeePositions = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): static
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setEmployee($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): static
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getEmployee() === $this) {
                $contract->setEmployee(null);
            }
        }

        return $this;
    }

    public function __toString() : string
    {
        return $this->getSecondName() . ' ' . $this->getFirstName() . ' ' . $this->getPatronymic();
    }

    /**
     * @return Collection<int, DaysOff>
     */
    public function getDaysOffs(): Collection
    {
        return $this->daysOffs;
    }

    public function addDaysOff(DaysOff $daysOff): static
    {
        if (!$this->daysOffs->contains($daysOff)) {
            $this->daysOffs->add($daysOff);
            $daysOff->setEmployee($this);
        }

        return $this;
    }

    public function removeDaysOff(DaysOff $daysOff): static
    {
        if ($this->daysOffs->removeElement($daysOff)) {
            // set the owning side to null (unless already changed)
            if ($daysOff->getEmployee() === $this) {
                $daysOff->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployeePosition>
     */
    public function getEmployeePositions(): Collection
    {
        return $this->employeePositions;
    }

    public function addEmployeePosition(EmployeePosition $employeePosition): static
    {
        if (!$this->employeePositions->contains($employeePosition)) {
            $this->employeePositions->add($employeePosition);
            $employeePosition->setEmployee($this);
        }

        return $this;
    }

    public function removeEmployeePosition(EmployeePosition $employeePosition): static
    {
        if ($this->employeePositions->removeElement($employeePosition)) {
            // set the owning side to null (unless already changed)
            if ($employeePosition->getEmployee() === $this) {
                $employeePosition->setEmployee(null);
            }
        }

        return $this;
    }
}
