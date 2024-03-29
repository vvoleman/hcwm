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
            ["cs","assets/images/flags/cs/width_40.png","čeština"],
            ["en","assets/images/flags/en/width_40.png","english"],
            ["de","assets/images/flags/de/width_40.png","deutsch"],
        ];

        foreach ($languages as $lang){
            $language = new Language(...$lang);
            $manager->persist($language);
        }

        $manager->flush();
    }
}
