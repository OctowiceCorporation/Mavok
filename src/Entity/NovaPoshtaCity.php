<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NovaPoshtaCityRepository")
 */
class NovaPoshtaCity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NovaPoshtaPostOffice", mappedBy="city")
     */
    private $novaPoshtaPostOffices;

    public function __construct()
    {
        $this->novaPoshtaPostOffices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|NovaPoshtaPostOffice[]
     */
    public function getNovaPoshtaPostOffices(): Collection
    {
        return $this->novaPoshtaPostOffices;
    }

    public function addNovaPoshtaPostOffice(NovaPoshtaPostOffice $novaPoshtaPostOffice): self
    {
        if (!$this->novaPoshtaPostOffices->contains($novaPoshtaPostOffice)) {
            $this->novaPoshtaPostOffices[] = $novaPoshtaPostOffice;
            $novaPoshtaPostOffice->setCity($this);
        }

        return $this;
    }

    public function removeNovaPoshtaPostOffice(NovaPoshtaPostOffice $novaPoshtaPostOffice): self
    {
        if ($this->novaPoshtaPostOffices->contains($novaPoshtaPostOffice)) {
            $this->novaPoshtaPostOffices->removeElement($novaPoshtaPostOffice);
            // set the owning side to null (unless already changed)
            if ($novaPoshtaPostOffice->getCity() === $this) {
                $novaPoshtaPostOffice->setCity(null);
            }
        }

        return $this;
    }
}
