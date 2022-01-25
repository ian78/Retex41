<?php

namespace App\Entity;

use DateTime;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;




/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * 
 * @ORM\HasLifecycleCallbacks()
 * 
 * @UNiqueENtity(
 * fields={"email"},
 * message="un autre utilisateur utilise déjà cette adresse")
 * 
 * 
 * 
 */
class User implements UserInterface 
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("user")
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "renseignez votre prénom")
     * @Groups("user")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "renseignez votre nom de famille")
     * @Groups("user")
     *
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "votre adresse mail n'est pas valide")
     * @Groups("user")
     */
    private $email;

 
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user")
     */
    private $hash;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(min=10, minMessage="votre description doit faire au moins 10 caractères")
     * @Groups("user")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("user")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Retex::class, mappedBy="author")
     * 
     */
    private $retexes;


    /**
     * 
     * @Assert\EqualTo(propertyPath="hash" , message="les mots de passe ne correspondent pas")
     * @Groups("user")
     * 
     */
    public $passwordConfirm;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Filename;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\OneToMany(targetEntity=Validation::class, mappedBy="validation")
     */
    private $validations;

    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="decision")
     */
    private $decisions;

      
   
  
    

    public function getFullName(){
        return "{$this->firstName} {$this->lastName}";
    }

    

    public function __construct()
    {
        $this->retexes = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->decisions = new ArrayCollection();
       
     
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

  
    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Retex[]
     */
    public function getRetexes(): Collection
    {
        return $this->retexes;
    }

    public function addRetex(Retex $retex): self
    {
        if (!$this->retexes->contains($retex)) {
            $this->retexes[] = $retex;
            $retex->setAuthor($this);
        }

        return $this;
    }

    public function removeRetex(Retex $retex): self
    {
        if ($this->retexes->contains($retex)) {
            $this->retexes->removeElement($retex);
            // set the owning side to null (unless already changed)
            if ($retex->getAuthor() === $this) {
                $retex->setAuthor(null);
            }
        }

        return $this;
    }

    /**
    * Permet d'initialiser le slug
    *
    * @ORM\PrePersist
    * @ORM\PreUpdate
    *
    * 
    */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug =$slugify->slugify($this->firstName . ''.$this->lastName);
        }
    }

    public function getRoles()
        {   
            $roles = $this->userRoles->map(function($role){
               return $role->getTitle(); 
            })->toArray();

            $roles[] = 'ROLE_USER';

             return $roles;
         }
    

   public function getPassword()
         {
             return $this->hash;
         }

    public function getSalt(){

    }

    public function getUsername(){
        return $this->email;
    }

    public function eraseCredentials(){

    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return ''.$this->id;
        
    }

    
   
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->Filename;
    }

    public function setFilename(?string $Filename): self
    {
        $this->Filename = $Filename;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations[] = $validation;
            $validation->setValidation($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->contains($validation)) {
            $this->validations->removeElement($validation);
            // set the owning side to null (unless already changed)
            if ($validation->getValidation() === $this) {
                $validation->setValidation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Decision[]
     */
    public function getDecisions(): Collection
    {
        return $this->decisions;
    }

    public function addDecision(Decision $decision): self
    {
        if (!$this->decisions->contains($decision)) {
            $this->decisions[] = $decision;
            $decision->setDecision($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->contains($decision)) {
            $this->decisions->removeElement($decision);
            // set the owning side to null (unless already changed)
            if ($decision->getDecision() === $this) {
                $decision->setDecision(null);
            }
        }

        return $this;
    }

   
    
}
