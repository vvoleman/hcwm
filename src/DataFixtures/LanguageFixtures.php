<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $languages = [
            ["cs","images/flags/cs/width_40.png","čeština"],
            ["en","images/flags/en/width_40.png","english"],
            ["de","images/flags/de/width_40.png","deutsch"],
        ];

        foreach ($languages as $lang){
            $language = new Language(...$lang);
            $manager->persist($language);
        }

        $manager->flush();
    }
}
