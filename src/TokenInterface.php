<?php

namespace Konfirm\QualityValue;


interface TokenInterface extends \JsonSerializable {
	/**
	 * @return string
	 */
	public function getValue(): string;

	/**
	 * @return float
	 */
	public function getWeight(): float;

	/**
	 * @return int
	 */
	public function getIndex(): int;

	/**
	 * @param TokenInterface $that
	 * @return int
	 */
	public function compareWeight(TokenInterface $that): int;

	/**
	 * @return string
	 */
	public function __toString(): string;
}
