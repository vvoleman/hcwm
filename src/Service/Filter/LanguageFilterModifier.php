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
		if(!in_array('il', $builder->getAllAliases())){
			$builder->innerJoin('i.itemsLanguages', 'il');
		}
		return $builder
			->andWhere(
				$builder->expr()->in('il.language', $this->languages)
			)
			//->andWhere('il.language IN (:languages)')
			->andHaving('COUNT(il.language) = :countLang')
			->groupBy('i.id')
			->setParameter("countLang",count($this->languages));
	}
}