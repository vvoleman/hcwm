<?php

namespace App\Tests\Service\Zotero\Entity;

use App\Service\Zotero\Entity\TranslatableZoteroEntity;
use PHPUnit\Framework\TestCase;

class TranslatableZoteroEntityTest extends TestCase
{

	/**
	 * @covers \App\Service\Zotero\Entity\TranslatableZoteroEntity::getNames
	 * @covers \App\Service\Zotero\PrepareLanguages::parse
	 * @dataProvider getNamesProvider
	 */
	public function testGetNames(string $name, ?array $result): void
	{
		$entity = $this->getMockForAbstractClass(TranslatableZoteroEntity::class, ['key', $name]);

		if ($result === null) {
			$this->expectException(\App\Exception\BadLanguageFormatException::class);
			dump($entity->getNames());
		} else {
			$this->assertEquals($result, $entity->getNames());
		}
	}

	public function getNamesProvider(): array
	{
		return [
			'✔️ Correct format, two languages' => [
				'cs__Český__en__English',
				[
					[
						'language' => 'cs',
						'text' => 'Český',
					],
					[
						'language' => 'en',
						'text' => 'English',
					],
				]
			],
			'✔️ Correct format, one language' => [
				'cs__Český',
				[
					[
						'language' => 'cs',
						'text' => 'Český',
					],
				]
			],
			'✔️ Correct format, no language' => [
				'Český',
				[
					[
						'language' => 'cs',
						'text' => 'Český',
					],
				]
			],
			'✔️ Bad format, but passable' => [
				'cs_Český_en_English',
				[
					[
						'language' => 'cs',
						'text' => 'cs_Český_en_English',
					],
				]
			],
			'❌ Incorrect format' => [
				'__cs__Český__en__English',
				null
			],
		];
	}
}
