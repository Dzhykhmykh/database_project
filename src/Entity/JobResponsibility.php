<?php

namespace App\Entity;

use App\Repository\JobResponsibilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobResponsibilityRepository::class)]
class JobResponsibility
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
     * @var Collection<int, JobTitle>
     */
    #[ORM\ManyToMany(targetEntity: JobTitle::class, mappedBy: 'jobResponsibilities')]
    private Collection $jobTitles;

    public function __construct()
    {
        $this->jobTitles = new ArrayCollection();
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
     * @return Collection<int, JobTitle>
     */
    public function getJobTitles(): Collection
    {
        return $this->jobTitles;
    }

    public function addJobTitle(JobTitle $jobTitle): static
    {
        if (!$this->jobTitles->contains($jobTitle)) {
            $this->jobTitles->add($jobTitle);
            $jobTitle->addJobResponsibility($this);
        }

        return $this;
    }

    public function removeJobTitle(JobTitle $jobTitle): static
    {
        if ($this->jobTitles->removeElement($jobTitle)) {
            $jobTitle->removeJobResponsibility($this);
        }

        return $this;
    }
}
