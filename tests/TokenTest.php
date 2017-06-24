<?php

use Konfirm\QualityValue\Token;

/**
 *  Test Token class
 */
class TokenTest extends PHPUnit\Framework\TestCase {
	public function testTokenDefaultQuality() {
		$token = new Token('foo');

		$this->assertEquals('foo', $token->getValue());
		$this->assertEquals(1, $token->getWeight());
		$this->assertEquals('foo', (string) $token);
		$this->assertJsonStringEqualsJsonString(
			json_encode(['value' => 'foo', 'weight' => 1, 'display' => 'foo']),
			json_encode($token)
		);

		unset($token);
	}

	public function testTokenGivenQuality() {
		$token = new Token('foo', 0.9);

		$this->assertEquals('foo', $token->getValue());
		$this->assertEquals(.9, $token->getWeight());
		$this->assertEquals('foo;q=.9', (string) $token);
		$this->assertJsonStringEqualsJsonString(
			json_encode(['value' => 'foo', 'weight' => .9, 'display' => 'foo;q=.9']),
			json_encode($token)
		);

		unset($token);
	}

	public function testTokenNegativeQualityCap() {
		$token = new Token('foo', -0.5);

		$this->assertEquals('foo', $token->getValue());
		$this->assertEquals(0, $token->getWeight());
		$this->assertEquals('', (string) $token);
		$this->assertJsonStringEqualsJsonString(
			json_encode(['value' => 'foo', 'weight' => 0, 'display' => '']),
			json_encode($token)
		);

		unset($token);
	}

	public function testTokenPositiveQualityCap() {
		$token = new Token('foo', 5);

		$this->assertEquals('foo', $token->getValue());
		$this->assertEquals(1, $token->getWeight());
		$this->assertEquals('foo', (string) $token);
		$this->assertJsonStringEqualsJsonString(
			json_encode(['value' => 'foo', 'weight' => 1, 'display' => 'foo']),
			json_encode($token)
		);

		unset($token);
	}

	public function testCompareQuality() {
		$foo = new Token('foo');
		$bar = new Token('bar', 0.5);
		$baz = new Token('baz');

		$this->assertEquals(0, $foo->compareWeight($foo));
		$this->assertEquals(-1, $foo->compareWeight($bar));
		$this->assertEquals(-1, $foo->compareWeight($baz));

		$this->assertEquals(1, $bar->compareWeight($foo));
		$this->assertEquals(0, $bar->compareWeight($bar));
		$this->assertEquals(1, $bar->compareWeight($baz));

		$this->assertEquals(1, $baz->compareWeight($foo));
		$this->assertEquals(-1, $baz->compareWeight($bar));
		$this->assertEquals(0, $baz->compareWeight($baz));

		unset($foo);
		unset($bar);
		unset($baz);
	}
}
