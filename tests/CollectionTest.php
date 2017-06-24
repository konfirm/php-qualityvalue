<?php

use Konfirm\QualityValue\Collection;

/**
 *  Test Collection class
 */
class CollectionTest extends PHPUnit_Framework_TestCase {
	public function testCollectionFromString() {
		$collection = Collection::fromString('a,b,c;q=0.8,d;q=0,e;q=0.90');

		$token = $collection->rewind();
		$this->assertEquals('a', $token->getValue());
		$this->assertEquals(1, $token->getWeight());
		$this->assertEquals('a', (string) $token);

		$token = $collection->next();
		$this->assertEquals('b', $token->getValue());
		$this->assertEquals(1, $token->getWeight());
		$this->assertEquals('b', (string) $token);

		$token = $collection->next();
		$this->assertEquals('e', $token->getValue());
		$this->assertEquals(.9, $token->getWeight());
		$this->assertEquals('e;q=.9', (string) $token);

		$token = $collection->next();
		$this->assertEquals('c', $token->getValue());
		$this->assertEquals(.8, $token->getWeight());
		$this->assertEquals('c;q=.8', (string) $token);

		$token = $collection->next();
		$this->assertEquals(null, $token);

		$this->assertEquals('a,b,e;q=.9,c;q=.8', (string) $collection);
		$this->assertJsonStringEqualsJsonString(
			json_encode([
				['value' => 'a', 'weight' => 1, 'display' => 'a'],
				['value' => 'b', 'weight' => 1, 'display' => 'b'],
				['value' => 'e', 'weight' => .9, 'display' => 'e;q=.9'],
				['value' => 'c', 'weight' => .8, 'display' => 'c;q=.8'],
			]),
			json_encode($collection)
		);

		unset($collection);
		unset($token);
	}

	public function testCollectionIterable() {
		$expect = [
			(object) ['value' => 'a', 'weight' => 1, 'display' => 'a'],
			(object) ['value' => 'e', 'weight' => .9, 'display' => 'e;q=.9'],
			(object) ['value' => 'c', 'weight' => .8, 'display' => 'c;q=.8'],
		];
		$cursor = 0;
		$collection = Collection::fromString('a,b;q=0,c;q=0.8,d;q=0,,,e;q=0.90');

		foreach ($collection as $token) {
			$this->assertEquals($cursor, $collection->key());
			$this->assertEquals($expect[$cursor]->value, $token->getValue());
			$this->assertEquals($expect[$cursor]->weight, $token->getWeight());
			$this->assertEquals($expect[$cursor]->display, (string) $token);
			$this->assertJsonStringEqualsJsonString(
				json_encode($expect[$cursor]),
				json_encode($token)
			);

			++$cursor;
		}

		unset($expect);
		unset($cursor);
		unset($collection);
	}
}