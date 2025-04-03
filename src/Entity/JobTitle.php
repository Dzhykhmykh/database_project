<?php

namespace App\Entity;

use App\Repository\JobTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JobTitleRepository::class)]
class JobTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $Salary = null;

    /**
     * @var Collection<int, JobResponsibility>
     */
    #[ORM\ManyToMany(targetEntity: JobResponsibility::class, inversedBy: 'jobTitles')]
    private Collection $jobResponsibilities;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->jobResponsibilities = new ArrayCollection();
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

    public function getSalary(): ?int
    {
        return $this->Salary;
    }

    public function setSalary(int $Salary): static
    {
        $this->Salary = $Salary;

        return $this;
    }

    /**
     * @return Collection<int, JobResponsibility>
     */
    public function getJobResponsibilities(): Collection
    {
        return $this->jobResponsibilities;
    }

    public function addJobResponsibility(JobResponsibility $jobResponsibility): static
    {
        if (!$this->jobResponsibilities->contains($jobResponsibility)) {
            $this->jobResponsibilities->add($jobResponsibility);
        }

        return $this;
    }

    public function removeJobResponsibility(JobResponsibility $jobResponsibility): static
    {
        $this->jobResponsibilities->removeElement($jobResponsibility);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTruncatedDescription(): ?string
    {
        return $this->description ? substr($this->description, 0, 20).'...' : null;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
