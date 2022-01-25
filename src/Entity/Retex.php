<?php

namespace App\Entity;

use App\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;


use App\Repository\RetexRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=RetexRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields={"titre"}
 * )
 * 
 * @ORM\Table(name="retex", 
 * indexes={@ORM\Index(columns={"objet" , "titre" , "generalites"} , 
 * flags={"fulltext"})})
 * 
 */
class Retex
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $objet;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reference;



    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $generalites;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $prepamission;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II1personnel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II1apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II1bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II2materiel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II2apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II2bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II2cameliration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II3technique;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II3bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II3camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III1personnel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III1apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III1bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III1camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III2materiel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III2apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III2bamelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III2camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IVretour;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV1personnel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV1apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV1bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV1camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV2materiel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV2apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV2bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV2camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV3technique;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV3apositif;

    

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV3camelioration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $conclusion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $II3apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III2bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $IV3bperfectible;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="retex")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III3apositif;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III3bperfectible;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $III3camelioration;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="retexes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="retex", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $piecejointe;

    /**
     * @ORM\OneToMany(targetEntity=Validation::class, mappedBy="retex")
     */
    private $validations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $standby;

    /**
     * @ORM\OneToMany(targetEntity=Decision::class, mappedBy="retex")
     */
    private $decisions;

    

  

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->validations = new ArrayCollection();
        $this->decisions = new ArrayCollection();
             
       
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
            $this->slug =$slugify->slugify($this->titre);
        }
    }

      
    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreatedAt = new \DateTime("now");
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
        
    }
    /**
     * Permet de recupérer l'avis d'un auteur par rapport à un retex
     *
     * @param User $author
     * @return Comment|null
     */
    public function getCommentFromAuthor(User $author){
        foreach($this->comments as $comment){
            if($comment->getAuthor() === $author) return $comment;
        }
        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }


    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getGeneralites(): ?string
    {
        return $this->generalites;
    }

    public function setGeneralites(?string $generalites): self
    {
        $this->generalites = $generalites;

        return $this;
    }

    public function getPrepamission(): ?string
    {
        return $this->prepamission;
    }

    public function setPrepamission(string $prepamission): self
    {
        $this->prepamission = $prepamission;

        return $this;
    }

    public function getII1personnel(): ?string
    {
        return $this->II1personnel;
    }

    public function setII1personnel(?string $II1personnel): self
    {
        $this->II1personnel = $II1personnel;

        return $this;
    }

    public function getII1apositif(): ?string
    {
        return $this->II1apositif;
    }

    public function setII1apositif(?string $II1apositif): self
    {
        $this->II1apositif = $II1apositif;

        return $this;
    }

    public function getII1bperfectible(): ?string
    {
        return $this->II1bperfectible;
    }

    public function setII1bperfectible(?string $II1bperfectible): self
    {
        $this->II1bperfectible = $II1bperfectible;

        return $this;
    }

    public function getII2materiel(): ?string
    {
        return $this->II2materiel;
    }

    public function setII2materiel(?string $II2materiel): self
    {
        $this->II2materiel = $II2materiel;

        return $this;
    }

    public function getII2apositif(): ?string
    {
        return $this->II2apositif;
    }

    public function setII2apositif(?string $II2apositif): self
    {
        $this->II2apositif = $II2apositif;

        return $this;
    }

    public function getII2bperfectible(): ?string
    {
        return $this->II2bperfectible;
    }

    public function setII2bperfectible(?string $II2bperfectible): self
    {
        $this->II2bperfectible = $II2bperfectible;

        return $this;
    }

    public function getII2cameliration(): ?string
    {
        return $this->II2cameliration;
    }

    public function setII2cameliration(?string $II2cameliration): self
    {
        $this->II2cameliration = $II2cameliration;

        return $this;
    }

    public function getII3technique(): ?string
    {
        return $this->II3technique;
    }

    public function setII3technique(?string $II3technique): self
    {
        $this->II3technique = $II3technique;

        return $this;
    }

    public function getII3bperfectible(): ?string
    {
        return $this->II3bperfectible;
    }

    public function setII3bperfectible(?string $II3bperfectible): self
    {
        $this->II3bperfectible = $II3bperfectible;

        return $this;
    }

    public function getII3camelioration(): ?string
    {
        return $this->II3camelioration;
    }

    public function setII3camelioration(?string $II3camelioration): self
    {
        $this->II3camelioration = $II3camelioration;

        return $this;
    }

    public function getIII1personnel(): ?string
    {
        return $this->III1personnel;
    }

    public function setIII1personnel(?string $III1personnel): self
    {
        $this->III1personnel = $III1personnel;

        return $this;
    }

    public function getIII1apositif(): ?string
    {
        return $this->III1apositif;
    }

    public function setIII1apositif(?string $III1apositif): self
    {
        $this->III1apositif = $III1apositif;

        return $this;
    }

    public function getIII1bperfectible(): ?string
    {
        return $this->III1bperfectible;
    }

    public function setIII1bperfectible(?string $III1bperfectible): self
    {
        $this->III1bperfectible = $III1bperfectible;

        return $this;
    }

    public function getIII1camelioration(): ?string
    {
        return $this->III1camelioration;
    }

    public function setIII1camelioration(?string $III1camelioration): self
    {
        $this->III1camelioration = $III1camelioration;

        return $this;
    }

    public function getIII2materiel(): ?string
    {
        return $this->III2materiel;
    }

    public function setIII2materiel(?string $III2materiel): self
    {
        $this->III2materiel = $III2materiel;

        return $this;
    }

    public function getIII2apositif(): ?string
    {
        return $this->III2apositif;
    }

    public function setIII2apositif(?string $III2apositif): self
    {
        $this->III2apositif = $III2apositif;

        return $this;
    }

    public function getIII2bamelioration(): ?string
    {
        return $this->III2bamelioration;
    }

    public function setIII2bamelioration(?string $III2bamelioration): self
    {
        $this->III2bamelioration = $III2bamelioration;

        return $this;
    }

    public function getIII2camelioration(): ?string
    {
        return $this->III2camelioration;
    }

    public function setIII2camelioration(?string $III2camelioration): self
    {
        $this->III2camelioration = $III2camelioration;

        return $this;
    }

    public function getIVretour(): ?string
    {
        return $this->IVretour;
    }

    public function setIVretour(?string $IVretour): self
    {
        $this->IVretour = $IVretour;

        return $this;
    }

    public function getIV1personnel(): ?string
    {
        return $this->IV1personnel;
    }

    public function setIV1personnel(?string $IV1personnel): self
    {
        $this->IV1personnel = $IV1personnel;

        return $this;
    }

    public function getIV1apositif(): ?string
    {
        return $this->IV1apositif;
    }

    public function setIV1apositif(?string $IV1apositif): self
    {
        $this->IV1apositif = $IV1apositif;

        return $this;
    }

    public function getIV1bperfectible(): ?string
    {
        return $this->IV1bperfectible;
    }

    public function setIV1bperfectible(?string $IV1bperfectible): self
    {
        $this->IV1bperfectible = $IV1bperfectible;

        return $this;
    }

    public function getIV1camelioration(): ?string
    {
        return $this->IV1camelioration;
    }

    public function setIV1camelioration(?string $IV1camelioration): self
    {
        $this->IV1camelioration = $IV1camelioration;

        return $this;
    }

    public function getIV2materiel(): ?string
    {
        return $this->IV2materiel;
    }

    public function setIV2materiel(?string $IV2materiel): self
    {
        $this->IV2materiel = $IV2materiel;

        return $this;
    }

    public function getIV2apositif(): ?string
    {
        return $this->IV2apositif;
    }

    public function setIV2apositif(?string $IV2apositif): self
    {
        $this->IV2apositif = $IV2apositif;

        return $this;
    }

    public function getIV2bperfectible(): ?string
    {
        return $this->IV2bperfectible;
    }

    public function setIV2bperfectible(?string $IV2bperfectible): self
    {
        $this->IV2bperfectible = $IV2bperfectible;

        return $this;
    }

    public function getIV2camelioration(): ?string
    {
        return $this->IV2camelioration;
    }

    public function setIV2camelioration(?string $IV2camelioration): self
    {
        $this->IV2camelioration = $IV2camelioration;

        return $this;
    }

    public function getIV3technique(): ?string
    {
        return $this->IV3technique;
    }

    public function setIV3technique(?string $IV3technique): self
    {
        $this->IV3technique = $IV3technique;

        return $this;
    }

    public function getIV3apositif(): ?string
    {
        return $this->IV3apositif;
    }

    public function setIV3apositif(string $IV3apositif): self
    {
        $this->IV3apositif = $IV3apositif;

        return $this;
    }

      public function getIV3camelioration(): ?string
    {
        return $this->IV3camelioration;
    }

    public function setIV3camelioration(?string $IV3camelioration): self
    {
        $this->IV3camelioration = $IV3camelioration;

        return $this;
    }

    public function getConclusion(): ?string
    {
        return $this->conclusion;
    }

    public function setConclusion(?string $conclusion): self
    {
        $this->conclusion = $conclusion;

        return $this;
    }

    public function getII3apositif(): ?string
    {
        return $this->II3apositif;
    }

    public function setII3apositif(?string $II3apositif): self
    {
        $this->II3apositif = $II3apositif;

        return $this;
    }

    public function getIII2bperfectible(): ?string
    {
        return $this->III2bperfectible;
    }

    public function setIII2bperfectible(?string $III2bperfectible): self
    {
        $this->III2bperfectible = $III2bperfectible;

        return $this;
    }

    public function getIV3bperfectible(): ?string
    {
        return $this->IV3bperfectible;
    }

    public function setIV3bperfectible(?string $IV3bperfectible): self
    {
        $this->IV3bperfectible = $IV3bperfectible;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIII3apositif(): ?string
    {
        return $this->III3apositif;
    }

    public function setIII3apositif(?string $III3apositif): self
    {
        $this->III3apositif = $III3apositif;

        return $this;
    }

    public function getIII3bperfectible(): ?string
    {
        return $this->III3bperfectible;
    }

    public function setIII3bperfectible(?string $III3bperfectible): self
    {
        $this->III3bperfectible = $III3bperfectible;

        return $this;
    }

    public function getIII3camelioration(): ?string
    {
        return $this->III3camelioration;
    }

    public function setIII3camelioration(?string $III3camelioration): self
    {
        $this->III3camelioration = $III3camelioration;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
            $comment->setRetex($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getRetex() === $this) {
                $comment->setRetex(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->titre;
        return $this->author;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getPiecejointe(): ?string
    {
        return $this->piecejointe;
    }

    public function setPiecejointe(?string $piecejointe) : self
    {
        $this->piecejointe = $piecejointe;

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
            $validation->setRetex($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->contains($validation)) {
            $this->validations->removeElement($validation);
            // set the owning side to null (unless already changed)
            if ($validation->getRetex() === $this) {
                $validation->setRetex(null);
            }
        }

        return $this;
    }

    public function getStandby(): ?bool
    {
        return $this->standby;
    }

    public function setStandby(?bool $standby): self
    {
        $this->standby = $standby;

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
            $decision->setRetex($this);
        }

        return $this;
    }

    public function removeDecision(Decision $decision): self
    {
        if ($this->decisions->contains($decision)) {
            $this->decisions->removeElement($decision);
            // set the owning side to null (unless already changed)
            if ($decision->getRetex() === $this) {
                $decision->setRetex(null);
            }
        }

        return $this;
    }

      
}
