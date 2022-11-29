<?php

namespace App\Tests\Service\Util;

use App\Service\Util\NormalizeChars;
use PHPUnit\Framework\TestCase;

class NormalizeCharsTest extends TestCase
{

	/**
	 * @covers \App\Service\Util\NormalizeChars::normalize
	 * @dataProvider provideNormalize
	 */
	public function testNormalize(string $input, string $expected): void
	{
		$actual = NormalizeChars::normalize($input);
		$this->assertSame($expected, $actual);
	}

	public function provideNormalize(): \Iterator
	{
		yield [
			'Kulička', 'Kulicka',
		];

		yield [
			'Nechť již hříšné saxofony ďáblů rozezvučí síň úděsnými tóny waltzu, tanga a quickstepu.', 'Necht jiz hrisne saxofony dablu rozezvuci sin udesnymi tony waltzu, tanga a quickstepu.',
		];

		yield [
			'123456', '123456'
		];
	}
}
