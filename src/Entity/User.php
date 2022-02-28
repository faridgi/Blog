<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @uniqueEntity(
 * fields = {"email"},
 * message= "L'email que vous avez choisi n'est plus disponible !")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 50,
     *          minMessage = "Votre prénom doit comporter au moins {{ limit }} caractères",
     *          maxMessage = "Votre prénom ne peut pas dépasser {{ limit }} caractères")
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *          min = 3,
     *          max = 50,
     *          minMessage = "Votre nom ne doit pas avoir moins de {{ limit }} caractères",
     *          maxMessage = "Votre nom ne peut pas dépasser {{ limit }} caractères")
     */
    private $lastname;

    /**
     * @Assert\Email(message="Email n'est pas valide !")
     * @ORM\Column(type="string", length=255)
     */
    private $email;


    /**
     * @Assert\length(min=6)
     * @ORM\Column(type="string", length=255)
     */
    private $password;
    
    /**
     * @Assert\EqualTo(propertyPath="password", message="Les deux mots de passe doivent être identiques")
     *
     */
    private $passwordConfirm;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="author")
     */
    private $articles;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

   
    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function __toString(){
        return $this->firstname.' '.$this->lastname;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    // public function addRoles(string $roles): self
    // {
    //     if (!in_array($roles, $this->roles)) {
    //         $this->roles[] = $roles;
    //     }

    //     return $this;
    // }


    public function getRoles(): array
    {
        $roles = $this->roles;

        // garantit que chaque user possède le rôle ROLE_USER
        // équivalent à array_push() qui ajoute un élément au tableau
        $roles[] = 'ROLE_USER';

        //array_unique élimine des doublons
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    public function getUserIdentifier(){
        return $this->getId();
    }

   
}
