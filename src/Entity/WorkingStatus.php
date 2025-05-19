<?php

namespace App\Entity;

use App\Repository\WorkingStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkingStatusRepository::class)]
class WorkingStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, EmployeePosition>
     */
    #[ORM\OneToMany(targetEntity: EmployeePosition::class, mappedBy: 'workingStatus')]
    private Collection $employeePositions;

    public function __construct()
    {
        $this->employeePositions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $employeePosition->setWorkingStatus($this);
        }

        return $this;
    }

    public function removeEmployeePosition(EmployeePosition $employeePosition): static
    {
        if ($this->employeePositions->removeElement($employeePosition)) {
            // set the owning side to null (unless already changed)
            if ($employeePosition->getWorkingStatus() === $this) {
                $employeePosition->setWorkingStatus(null);
            }
        }

        return $this;
    }
}
