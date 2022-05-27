<?php

namespace App\Entity;

use App\Service\Zotero\PrepareLanguages;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ItemRepository")]
#[ORM\Table("items")]
class Item
{

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $url;

    #[ORM\Column(type: "datetime",nullable: true)]
    private ?\DateTime $date;

    #[ORM\OneToMany(mappedBy: "item", targetEntity: "App\Entity\Author",cascade: ["persist"])]
    private \Doctrine\Common\Collections\Collection $authors;

    #[ORM\OneToMany(mappedBy: "item", targetEntity: "App\Entity\ItemLanguage")]
    private \Doctrine\Common\Collections\Collection $itemsLanguages;

    #[ORM\ManyToMany(targetEntity: "App\Entity\Tag",inversedBy: "items")]
    private \Doctrine\Common\Collections\Collection $tags;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Collection", inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false,onDelete: "CASCADE")]
    private ?Collection $collection;

	#[ORM\ManyToOne(targetEntity: "App\Entity\Language")]
	#[ORM\JoinColumn(referencedColumnName: "code", nullable: false)]
	private Language $language;

	#[ORM\Column(type: 'text')]
	private string $abstract;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $dateAdded;

	#[ORM\Column(type: 'datetime_immutable')]
	private DateTimeImmutable $dateModified;

    public function __construct() {
        $this->authors = new ArrayCollection();
        $this->itemsLanguages = new ArrayCollection();
		$this->tags = new ArrayCollection();
    }

    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setTags(array $tags): self{
        $this->tags = new ArrayCollection($tags);
        return $this;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Item
    {
        $this->url = $url;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): Item
    {
        $this->date = $date;
        return $this;
    }

    public function setAuthors(array $authors): self
    {
        $this->authors = new ArrayCollection($authors);

        return $this;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
            $author->setItem($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getItem() === $this) {
                $author->setItem(null);
            }
        }

        return $this;
    }


    public function getCollection(): ?Collection
    {
        return $this->collection;
    }

    public function setCollection(?Collection $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

	public function addTag(Tag $tag){
		if (!$this->tags->contains($tag)) {
			$this->tags[] = $tag;
		}

		return $this;
	}

    public function getName(string $language): string
    {

        $result = $this->itemsLanguages->filter(function($colLang) use($language){
            return $colLang->getLanguage()->getCode() === $language;
        });

        if(count($result) === 0){
            if($language === PrepareLanguages::DEFAULT_LANGUAGE){
                if(count($this->itemsLanguages) === 0){
                    return "-";
                }else{
                    return $this->itemsLanguages->first()->getText();
                }
            }else{
                return $this->getName(PrepareLanguages::DEFAULT_LANGUAGE);
            }
        }

        return $result->first()->getText() ?? "";
    }

	/**
	 * @return ArrayCollection|\Doctrine\Common\Collections\Collection
	 */
	public function getItemsLanguages(): ArrayCollection|\Doctrine\Common\Collections\Collection
	{
		return $this->itemsLanguages;
	}

	/**
	 * @param ArrayCollection|\Doctrine\Common\Collections\Collection $itemsLanguages
	 * @return Item
	 */
	public function setItemsLanguages(ArrayCollection|\Doctrine\Common\Collections\Collection $itemsLanguages): Item
	{
		$this->itemsLanguages = $itemsLanguages;
		return $this;
	}

	public function getAbstract(): string
	{
		return $this->abstract;
	}

	public function setAbstract(string $abstract): Item
	{
		$this->abstract = $abstract;
		return $this;
	}

	/**
	 * @return Tag[]
	 */
	public function getTags(): array
	{
		return $this->tags->toArray();
	}

	/**
	 * @return Language[]
	 */
	public function getLanguages(): array{
		return $this->itemsLanguages->map(function($x){
			return $x->getLanguage();
		})->toArray();
	}


	public function getLanguage(): Language{
		return $this->language;
	}

	public function setLanguage(Language $language): self
	{
		$this->language = $language;

		return $this;
	}

	public function getItemLanguages(): \Doctrine\Common\Collections\Collection {
		return $this->itemsLanguages;
	}

	public function getDateAdded(): DateTimeImmutable
	{
		return $this->dateAdded;
	}

	public function setDateAdded(DateTimeImmutable $dateAdded): Item
	{
		$this->dateAdded = $dateAdded;
		return $this;
	}

	public function getDateModified(): DateTimeImmutable
	{
		return $this->dateModified;
	}

	public function setDateModified(DateTimeImmutable $dateModified): Item
	{
		$this->dateModified = $dateModified;
		return $this;
	}



}