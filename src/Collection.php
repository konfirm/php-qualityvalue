<?php

namespace Konfirm\QualityValue;


class Collection implements \Iterator, \JsonSerializable {
	/**
	 * @var TokenInterface[]
	 */
	protected $tokens;

	/**
	 * Collection constructor.
	 * @param TokenInterface[] ...$tokens
	 */
	public function __construct(TokenInterface ...$tokens) {
		$this->tokens = array_values(array_filter($tokens, function(TokenInterface $token) {
			return strlen($token) > 0;
		}));
	}

	/**
	 * @param \Closure $callback
	 * @return Collection
	 */
	public function filter(\Closure $callback) {
		return new Collection(...array_filter($this->tokens, $callback));
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

	/**
	 * @return TokenInterface|mixed
	 */
	public function current() {
		return current($this->tokens);
	}

	/**
	 * @return TokenInterface|mixed
	 */
	public function next() {
		return next($this->tokens);
	}

	/**
	 * @return mixed
	 */
	public function key() {
		return key($this->tokens);
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return key($this->tokens) !== null;
	}

	/**
	 * @return TokenInterface|mixed
	 */
	public function rewind() {
		return reset($this->tokens);
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return implode(',', $this->tokens);
	}

	/**
	 * @return TokenInterface[]
	 */
	function jsonSerialize() {
		return $this->tokens;
	}
}
