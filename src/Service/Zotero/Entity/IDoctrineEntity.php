<?php

namespace App\Service\Zotero\Entity;

use Doctrine\ORM\EntityManagerInterface;

interface IDoctrineEntity
{

	public function makeDoctrineEntity(EntityManagerInterface $manager): mixed;

}