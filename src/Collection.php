<?php

namespace Konfirm\QualityValue;

use Konfirm\Collection\Provider;


class Collection extends Provider implements \JsonSerializable {
	/**
	 * Collection constructor.
	 * @param TokenInterface[] ...$tokens
	 */
	public function __construct(TokenInterface ...$tokens) {
		parent::__construct(...array_values(array_filter($tokens, 'strlen')));
	}

	/**
	 * @param $value
	 * @return TokenInterface|mixed
	 */
	public function findTokenByValue($value) {
		$filtered = $this->filter(function(TokenInterface $token) use ($value) {
			return $token->getValue() === $value;
		});

		return $filtered->rewind();
	}

	/**
	 * @param $quality
	 * @return Collection
	 */
	public static function fromString($quality) {
		return new Collection(...Parser::parse($quality));
	}

	public function intersect(Provider $provider): Provider {
		return Collection::fromString((string) parent::intersect($provider));
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return implode(',', array_values(array_filter($this->source, 'strlen')));
	}

	/**
	 * @return array
	 */
	function jsonSerialize() {
		return $this->source;
	}
}
