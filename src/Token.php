<?php

namespace Konfirm\QualityValue;

use Konfirm\Collection\Comparable;

class Token implements TokenInterface, Comparable {
	/**
	 * @var int
	 */
	protected static $count = 0;

	/**
	 * @var string
	 */
	protected $value;

	/**
	 * @var float
	 */
	protected $weight;

	/**
	 * @var int
	 */
	protected $index;

	/**
	 * Token constructor.
	 * @param string $value
	 * @param float $weight
	 */
	public function __construct(string $value, float $weight = null) {
		$this->value = $value;
		$this->weight = max(0, min(1, is_null($weight) ? 1 : $weight));
		$this->index = static::$count++;
	}

	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}

	/**
	 * @return float
	 */
	public function getWeight(): float {
		return $this->weight;
	}

	/**
	 * @return int
	 */
	public function getIndex(): int {
		return $this->index;
	}

	/**
	 * @return string
	 */
	public function getComparison() {
		return $this->getValue();
	}

	/**
	 * @param Token $that
	 * @return int
	 * @note a greater (or equal and defined earlier) 
	 */
	public function compareWeight(TokenInterface $that): int {
		$na = $this->getWeight();
		$nb = $that->getWeight();

		//  if the quality specification is the same, use the index
		if ($na === $nb) {
			$na = $that->getIndex();
			$nb = $this->getIndex();
		}

		//  ensure the possible return values to be -1, 0 or 1
		return $na > $nb ? -1 : (int) ($na < $nb);
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		//  the shortest possible string representation is created:
		//  - no ';q=1' is appended, as 1 is the default
		//  - leading and trailing 0 are stripped
		//  - values smaller than 0.001 are considered "not acceptable" and therefor stripped
		if ($this->weight < 0.001) {
			return '';
		}

		$quality = $this->weight < 1 ? sprintf(';q=%s', trim($this->weight, '0')) : '';

		return sprintf('%s%s', $this->value, $quality);
	}

	/**
	 * @return object
	 */
	function jsonSerialize() {
		return (object) [
			'value' => $this->getValue(),
			'weight' => $this->getWeight(),
			'display' => (string) $this,
		];
	}
}
