<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\PrePersist;
use App\Repository\ValidationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ValidationRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Validation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_submit;



    /**
     * @ORM\ManyToOne(targetEntity=Retex::class, inversedBy="validations")
     */
    private $retex;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="validations")
     */
    private $validation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $standby;


        /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setDateSubmit = new \DateTime("now");
        if ($this->getDateSubmit() === null) {
            $this->setDateSubmit(new \DateTime('now'));
        }
        
    }

    public function __toString()
    {
        return $this->id;
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateSubmit(): ?\DateTimeInterface
    {
        return $this->date_submit;
    }

    public function setDateSubmit(?\DateTimeInterface $date_submit): self
    {
        $this->date_submit = $date_submit;

        return $this;
    }



    public function getRetex(): ?Retex
    {
        return $this->retex;
    }

    public function setRetex(?Retex $retex): self
    {
        $this->retex = $retex;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getValidation(): ?User
    {
        return $this->validation;
    }

    public function setValidation(?User $validation): self
    {
        $this->validation = $validation;

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
}
