<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commandes")
     */
    private $Utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TableRestaurant", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $TableRestaurant;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Dish", inversedBy="commandes")
     */
    private $Dishes;

    /**
     * @ORM\Column(type="float")
     */
    private $prixTotal;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $status;

    public function __construct()
    {
        $this->Dishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?User $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getTableRestaurant(): ?TableRestaurant
    {
        return $this->TableRestaurant;
    }

    public function setTableRestaurant(?TableRestaurant $TableRestaurant): self
    {
        $this->TableRestaurant = $TableRestaurant;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDishes(): Collection
    {
        return $this->Dishes;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->Dishes->contains($dish)) {
            $this->Dishes[] = $dish;
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->Dishes->contains($dish)) {
            $this->Dishes->removeElement($dish);
        }

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
