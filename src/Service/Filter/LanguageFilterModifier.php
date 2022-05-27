<?php
declare(strict_types=1);


namespace App\Service\Filter;

use Doctrine\ORM\QueryBuilder;

class LanguageFilterModifier implements IFilterModifier
{

	/** @var string[]  */
	private array $languages;

	/**
	 * @param string[] $languages
	 */
	public function __construct(array $languages) {
		$this->languages = $languages;
	}

	public function process(QueryBuilder $builder): QueryBuilder
	{
		return $builder
			->innerJoin('i.itemsLanguages', 'il')
			->andWhere('il.language IN (:languages)')
			->andHaving('COUNT(il.language) = :count')
			->groupBy('i.id')
			->setParameter('languages', $this->languages)
			->setParameter("count",count($this->languages));
	}
}