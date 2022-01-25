<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Retex::class, mappedBy="categorie")
     */
    private $retex;

    public function __construct()
    {
        $this->retex = new ArrayCollection();
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
            $this->slug =$slugify->slugify($this->name);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|Retex[]
     */
    public function getRetex(): Collection
    {
        return $this->retex;
    }

    public function addRetex(Retex $retex): self
    {
        if (!$this->retex->contains($retex)) {
            $this->retex[] = $retex;
            $retex->setCategorie($this);
        }

        return $this;
    }

    public function removeRetex(Retex $retex): self
    {
        if ($this->retex->contains($retex)) {
            $this->retex->removeElement($retex);
            // set the owning side to null (unless already changed)
            if ($retex->getCategorie() === $this) {
                $retex->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
        
    }
}
