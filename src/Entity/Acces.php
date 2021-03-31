<?php

namespace App\Entity;

use App\Repository\AccesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccesRepository::class)
 */
class Acces
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="AutorisationId")
     * @ORM\JoinColumn(nullable=false)
     */
    private $UtilisateurId;

    /**
     * @ORM\ManyToOne(targetEntity=Autorisation::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $AutorisationId;

    /**
     * @ORM\ManyToOne(targetEntity=Document::class, inversedBy="acces")
     * @ORM\JoinColumn(nullable=false)
     */
    private $DocumentId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateurId(): ?Utilisateur
    {
        return $this->UtilisateurId;
    }

    public function setUtilisateurId(?Utilisateur $UtilisateurId): self
    {
        $this->UtilisateurId = $UtilisateurId;

        return $this;
    }

    public function getAutorisationId(): ?Autorisation
    {
        return $this->AutorisationId;
    }

    public function setAutorisationId(?Autorisation $AutorisationId): self
    {
        $this->AutorisationId = $AutorisationId;

        return $this;
    }

    public function getDocumentId(): ?Document
    {
        return $this->DocumentId;
    }

    public function setDocumentId(?Document $DocumentId): self
    {
        $this->DocumentId = $DocumentId;

        return $this;
    }
}
