<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur
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
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Password;

    /**
     * @ORM\OneToMany(targetEntity=Acces::class, mappedBy="UtilisateurId")
     */
    private $AutorisationId;

    public function __construct()
    {
        $this->AutorisationId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * @return Collection|Acces[]
     */
    public function getAutorisationId(): Collection
    {
        return $this->AutorisationId;
    }

    public function addAutorisationId(Acces $autorisationId): self
    {
        if (!$this->AutorisationId->contains($autorisationId)) {
            $this->AutorisationId[] = $autorisationId;
            $autorisationId->setUtilisateurId($this);
        }

        return $this;
    }

    public function removeAutorisationId(Acces $autorisationId): self
    {
        if ($this->AutorisationId->removeElement($autorisationId)) {
            // set the owning side to null (unless already changed)
            if ($autorisationId->getUtilisateurId() === $this) {
                $autorisationId->setUtilisateurId(null);
            }
        }

        return $this;
    }
}
