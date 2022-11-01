<?php
declare(strict_types=1);


namespace App\Service\Zotero\Entity;

use App\Entity\ItemLanguage;
use App\Entity\Language;
use App\Entity\Tag;
use App\Repository\LanguageRepository;
use App\Service\Zotero\Exception\Entity\InvalidLanguageException;
use App\Service\Zotero\PrepareLanguages;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class Item extends TranslatableZoteroEntity
{

	private string $url;

	/** @var Author[] */
	private array $authors = [];

	private string $abstractNote;
	private LanguageEnum $language;
	private array $tags = [];
	private \DateTimeImmutable $dateAdded;
	private \DateTimeImmutable $dateModified;
	private ?\DateTimeImmutable $date = null;

	/**
	 * @param string $itemType
	 * @param string $title
	 * @param string $url
	 * @param Author[] $authors
	 * @param string $abstractNote
	 * @param LanguageEnum $language
	 * @param array $tags
	 * @param \DateTimeImmutable $dateAdded
	 * @param \DateTimeImmutable $dateModified
	 * @param \DateTimeImmutable|null $date
	 */
	public function __construct(
		string $key,
		string $title,
		string $url,
		array $authors,
		string $abstractNote,
		LanguageEnum $language,
		array $tags,
		\DateTimeImmutable $dateAdded,
		\DateTimeImmutable $dateModified,
		?\DateTimeImmutable $date = null,
		string $parentKey = null
	) {
		parent::__construct($key, $title, $parentKey);
		$this->url = $url;
		$this->authors = $authors;
		$this->abstractNote = $abstractNote;
		$this->language = $language;
		$this->tags = $tags;
		$this->dateAdded = $dateAdded;
		$this->dateModified = $dateModified;
		$this->date = $date;
	}

	public function makeDoctrineEntity(EntityManagerInterface $manager): \App\Entity\Item
	{
		// If already exists, remove it
		$itemRepository = $manager->getRepository(\App\Entity\Item::class);
		$item = $itemRepository->find($this->getKey());
		if ($item) {
			$manager->remove($item);
		}

		$item = new \App\Entity\Item();
		$item->setId($this->getKey());
		$item->setUrl($this->url);
		$item->setAbstract($this->abstractNote);
		$item->setDateAdded($this->dateAdded);
		$item->setDateModified($this->dateModified);

		if ($this->date) {
			$item->setDate(\DateTime::createFromImmutable($this->date));
		}

		$authors = [];
		foreach ($this->authors as $author) {
			$authors[] = $author->makeDoctrineEntity($manager);
		}
		$item->setAuthors($authors);

		$tags = [];
		$tagRepository = $manager->getRepository(Tag::class);
		foreach ($this->tags as $text) {
			$tag = $tagRepository->findOneBy(['text' => $text]);

			if (!$tag) {
				$tag = new Tag();
				$tag->setText($text);
				$manager->persist($tag);
			}

			$tags[] = $tag;
		}
		$item->setTags($tags);

		/** @var LanguageRepository $languageRepository */
		$languageRepository = $manager->getRepository(Language::class);
		$language = $languageRepository->find($this->language->value);

		if ($language === null) {
			throw new InvalidLanguageException(sprintf('Language "%s" not found', $this->language->value));
		}
		$item->setLanguage($language);

		$translations = (new PrepareLanguages($languageRepository))->prepare($this->name);
		$itemLanguages = new ArrayCollection();
		foreach ($translations as $translation) {
			$itemLanguage = new ItemLanguage();
			$itemLanguage->setLanguage($translation->getLanguage());
			$itemLanguage->setText($translation->getText());
			$itemLanguage->setItem($item);

			$itemLanguages->add($itemLanguage);
			$manager->persist($itemLanguage);
		}

		$item->setItemsLanguages($itemLanguages);

		$manager->persist($item);

		return $item;
	}


}