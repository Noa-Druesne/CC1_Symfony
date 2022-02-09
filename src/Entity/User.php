<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"nom"}, message="There is already an account with this nom")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $nom;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $prenom;

    #[ORM\Column(type: 'json')]
    private $roles = ['ROLE_ENFANT'];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'animateur', targetEntity: Activite::class)]
    private $activite;

    #[ORM\ManyToMany(targetEntity: Activite::class, inversedBy: 'users')]
    private $enfants;

    public function __construct()
    {
        $this->activite = new ArrayCollection();
        $this->enfants = new ArrayCollection();
    }

    public function __toString(){
        return $this->getNom()." ".$this->getPrenom();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->nom;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->nom;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivite(): Collection
    {
        return $this->activite;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activite->contains($activite)) {
            $this->activite[] = $activite;
            $activite->setAnimateur($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activite->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getAnimateur() === $this) {
                $activite->setAnimateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Activite[]
     */
    public function getEnfants(): Collection
    {
        return $this->enfants;
    }

    public function addEnfant(Activite $enfant): self
    {
        if (!$this->enfants->contains($enfant)) {
            $this->enfants[] = $enfant;
        }

        return $this;
    }

    public function removeEnfant(Activite $enfant): self
    {
        $this->enfants->removeElement($enfant);

        return $this;
    }
}
