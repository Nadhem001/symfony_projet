<?php

namespace App\Entity;

use App\Repository\AuteursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuteursRepository::class)
 */
class Auteurs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_livre;

    /**
     * @ORM\OneToMany(targetEntity=Livres::class, mappedBy="auteurs")
     */
    private $livre;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getnom(): ?string
    {
        return $this->nom;
    }

    public function setnom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbLivre(): ?int
    {
        return $this->nb_livre;
    }

    public function setNbLivre(?int $nb_livre): self
    {
        $this->nb_livre = $nb_livre;

        return $this;
    }

    /**
     * @return Collection<int, Livres>
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livres $livre): self
    {
        if (!$this->livre->contains($livre)) {
            $this->livre[] = $livre;
            $livre->setAuteurs($this);
        }

        return $this;
    }

    public function removeLivre(Livres $livre): self
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteurs() === $this) {
                $livre->setAuteurs(null);
            }
        }

        return $this;
    }

}
