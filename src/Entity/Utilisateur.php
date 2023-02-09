<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Formation::class)]
    private Collection $formations;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: FormationAchete::class)]
    private Collection $formationAchetes;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->formationAchetes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setAuthor($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getAuthor() === $this) {
                $formation->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FormationAchete>
     */
    public function getFormationAchetes(): Collection
    {
        return $this->formationAchetes;
    }

    public function addFormationAchete(FormationAchete $formationAchete): self
    {
        if (!$this->formationAchetes->contains($formationAchete)) {
            $this->formationAchetes->add($formationAchete);
            $formationAchete->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFormationAchete(FormationAchete $formationAchete): self
    {
        if ($this->formationAchetes->removeElement($formationAchete)) {
            // set the owning side to null (unless already changed)
            if ($formationAchete->getUtilisateur() === $this) {
                $formationAchete->setUtilisateur(null);
            }
        }

        return $this;
    }
}
