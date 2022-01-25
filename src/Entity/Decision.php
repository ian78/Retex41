<?php

namespace App\Entity;

use App\Repository\DecisionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DecisionRepository::class)
 */
class Decision
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Retex::class, inversedBy="decisions")
     */
    private $retex;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="decisions")
     */
    private $decision;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

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

    public function getDecision(): ?User
    {
        return $this->decision;
    }

    public function setDecision(?User $decision): self
    {
        $this->decision = $decision;

        return $this;
    }
}
