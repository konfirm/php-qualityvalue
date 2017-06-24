<?php 

use Konfirm\QualityValue\Parser;
use Konfirm\QualityValue\Token;

/**
 *  Test Parser class
 */
class ParserTest extends PHPUnit_Framework_TestCase {
	public function testParsesWithoutQuality() {
		$tokens = Parser::parse('a,b,c');

		$this->assertCount(3, $tokens);

		$this->assertInstanceOf(Token::class, $tokens[0]);
		$this->assertEquals('a', $tokens[0]->getValue());
		$this->assertEquals(1, $tokens[0]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[1]);
		$this->assertEquals('b', $tokens[1]->getValue());
		$this->assertEquals(1, $tokens[1]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[2]);
		$this->assertEquals('c', $tokens[2]->getValue());
		$this->assertEquals(1, $tokens[2]->getWeight());

		unset($tokens);
	}

	public function testParsesWithEqualQuality() {
		$tokens = Parser::parse('a;q=.2,b;q=0.2,c;q=0.20');

		$this->assertCount(3, $tokens);

		$this->assertInstanceOf(Token::class, $tokens[0]);
		$this->assertEquals('a', $tokens[0]->getValue());
		$this->assertEquals(.2, $tokens[0]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[1]);
		$this->assertEquals('b', $tokens[1]->getValue());
		$this->assertEquals(.2, $tokens[1]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[2]);
		$this->assertEquals('c', $tokens[2]->getValue());
		$this->assertEquals(.2, $tokens[2]->getWeight());

		unset($tokens);
	}

	public function testReordersOnUnequalQuality() {
		$tokens = Parser::parse('a;q=.4,b;q=0.7,c');

		$this->assertCount(3, $tokens);

		$this->assertInstanceOf(Token::class, $tokens[0]);
		$this->assertEquals('c', $tokens[0]->getValue());
		$this->assertEquals(1, $tokens[0]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[1]);
		$this->assertEquals('b', $tokens[1]->getValue());
		$this->assertEquals(.7, $tokens[1]->getWeight());

		$this->assertInstanceOf(Token::class, $tokens[2]);
		$this->assertEquals('a', $tokens[2]->getValue());
		$this->assertEquals(.4, $tokens[2]->getWeight());

		unset($tokens);
	}

	public function testRegeneratesTokenString() {
		$tokens = Parser::parse('a;q=0.4,b;q=0.7,c;q=1.0');

		$this->assertCount(3, $tokens);

		$this->assertInstanceOf(Token::class, $tokens[0]);
		$this->assertEquals('c', $tokens[0]->getValue());
		$this->assertEquals(1, $tokens[0]->getWeight());
		$this->assertEquals('c', (string) $tokens[0]);

		$this->assertInstanceOf(Token::class, $tokens[1]);
		$this->assertEquals('b', $tokens[1]->getValue());
		$this->assertEquals(.7, $tokens[1]->getWeight());
		$this->assertEquals('b;q=.7', (string) $tokens[1]);

		$this->assertInstanceOf(Token::class, $tokens[2]);
		$this->assertEquals('a', $tokens[2]->getValue());
		$this->assertEquals(.4, $tokens[2]->getWeight());
		$this->assertEquals('a;q=.4', (string) $tokens[2]);

		unset($tokens);
	}

	public function testDoesNotTripOnInvalidValues() {
		$tokens = Parser::parse('a,,cdefgh;q12,,b;q=0000.70000,q;q=100,negative;q=-.2');

		$this->assertCount(5, $tokens);

		$this->assertInstanceOf(Token::class, $tokens[0]);
		$this->assertEquals('a', $tokens[0]->getValue());
		$this->assertEquals(1, $tokens[0]->getWeight());
		$this->assertEquals('a', (string) $tokens[0]);

		$this->assertInstanceOf(Token::class, $tokens[1]);
		$this->assertEquals('cdefgh', $tokens[1]->getValue());
		$this->assertEquals(1, $tokens[1]->getWeight());
		$this->assertEquals('cdefgh', (string) $tokens[1]);

		$this->assertInstanceOf(Token::class, $tokens[2]);
		$this->assertEquals('q', $tokens[2]->getValue());
		$this->assertEquals(1, $tokens[2]->getWeight());
		$this->assertEquals('q', (string) $tokens[2]);

		$this->assertInstanceOf(Token::class, $tokens[3]);
		$this->assertEquals('b', $tokens[3]->getValue());
		$this->assertEquals(.7, $tokens[3]->getWeight());
		$this->assertEquals('b;q=.7', (string) $tokens[3]);

		$this->assertInstanceOf(Token::class, $tokens[4]);
		$this->assertEquals('negative', $tokens[4]->getValue());
		$this->assertEquals(0, $tokens[4]->getWeight());
		$this->assertEquals('', (string) $tokens[4]);

		unset($tokens);
	}
}
