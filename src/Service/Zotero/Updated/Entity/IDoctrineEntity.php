<?php

namespace App\Service\Zotero\Updated\Entity;

use Doctrine\ORM\EntityManagerInterface;

interface IDoctrineEntity
{

	public function makeDoctrineEntity(EntityManagerInterface $manager): mixed;

}