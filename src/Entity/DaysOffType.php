<?php

namespace App\Entity;

use App\Repository\DaysOffTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DaysOffTypeRepository::class)]
class DaysOffType
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
     * @var Collection<int, DaysOff>
     */
    #[ORM\OneToMany(targetEntity: DaysOff::class, mappedBy: 'daysOffType')]
    private Collection $daysOffs;

    public function __construct()
    {
        $this->daysOffs = new ArrayCollection();
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
            $daysOff->setDaysOffType($this);
        }

        return $this;
    }

    public function removeDaysOff(DaysOff $daysOff): static
    {
        if ($this->daysOffs->removeElement($daysOff)) {
            // set the owning side to null (unless already changed)
            if ($daysOff->getDaysOffType() === $this) {
                $daysOff->setDaysOffType(null);
            }
        }

        return $this;
    }
}
