<?php

namespace App\Entity;

use App\Repository\LivresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LivresRepository::class)
 */
class Livres
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=6000)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="date")
     */
    private $date_sortie;

    /**
     * @ORM\Column(type="date")
     */
    private $date_ajout;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path_cover;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path_livre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $disp;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug_livre;

    /**
     * @ORM\ManyToOne(targetEntity=Auteurs::class, inversedBy="livre")
     */
    private $auteurs;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, mappedBy="livres")
     */
    private $categories;
    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="livre")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Emprunt::class, mappedBy="livre_id")
     */
    private $emprunts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $en_stocke;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->date_sortie;
    }

    public function setDateSortie(\DateTimeInterface $date_sortie): self
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getPathCover(): ?string
    {
        return $this->path_cover;
    }

    public function setPathCover(string $path_cover): self
    {
        $this->path_cover = $path_cover;

        return $this;
    }

    public function getPathLivre(): ?string
    {
        return $this->path_livre;
    }

    public function setPathLivre(string $path_livre): self
    {
        $this->path_livre = $path_livre;

        return $this;
    }

    public function isDisp(): ?bool
    {
        return $this->disp;
    }

    public function setDisp(?bool $disp): self
    {
        $this->disp = $disp;

        return $this;
    }


    public function getSlugLivre(): ?string
    {
        return $this->slug_livre;
    }

    public function setSlugLivre(string $slug_livre): self
    {
        $this->slug_livre = $slug_livre;

        return $this;
    }

    public function getAuteurs(): ?Auteurs
    {
        return $this->auteurs;
    }

    public function setAuteurs(?Auteurs $auteurs): self
    {
        $this->auteurs = $auteurs;

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addLivre($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeLivre($this);
        }

        return $this;
    }
    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLivre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLivre() === $this) {
                $reservation->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function isEnStocke(): ?bool
    {
        return $this->en_stocke;
    }

    public function setEnStocke(bool $en_stocke): self
    {
        $this->en_stocke = $en_stocke;

        return $this;
    }

}
