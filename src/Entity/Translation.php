<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Language::class)]
	#[ORM\JoinColumn(referencedColumnName: "code", nullable: false, onDelete: "CASCADE")]
    private Language $fromLanguage;

    #[ORM\ManyToOne(targetEntity: Language::class,)]
    #[ORM\JoinColumn(referencedColumnName: "code", nullable: false, onDelete: "CASCADE")]
    private Language $toLanguage;

    #[ORM\Column(type: 'text')]
    private string $text;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

	public function __construct() {
		$this->createdAt = new \DateTimeImmutable();
	}

	public function setId(string $id): self{
		$this->id = $id;

		return $this;
	}

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFromLanguage(): ?Language
    {
        return $this->fromLanguage;
    }

    public function setFromLanguage(?Language $fromLanguage): self
    {
        $this->fromLanguage = $fromLanguage;

        return $this;
    }

    public function getToLanguage(): ?Language
    {
        return $this->toLanguage;
    }

    public function setToLanguage(?Language $toLanguage): self
    {
        $this->toLanguage = $toLanguage;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
