<?php

namespace Konfirm\QualityValue;


class Parser {
	/**
	 * @param string $input
	 * @return array
	 */
	public static function parse($input): array {
		$list = array_map('static::token', preg_split('/\s*,\s*/', $input));
		$tokens = array_filter($list);

		usort($tokens, 'static::sort');

		return $tokens;
	}

	/**
	 * @param string $input
	 * @return Token|null
	 */
	protected static function token($input) {
		if (preg_match('/^(?<value>[^;]+)(?:;q=(?<weight>-?[0-9]*(?:\.[0-9]+)?))?/i', $input, $match)) {
			$weight = isset($match['weight']) ? (float) $match['weight'] : 1;

			return new Token($match['value'], $weight);
		}

		return null;
	}

	/**
	 * @param Token $a
	 * @param Token $b
	 * @return int
	 */
	protected static function sort(Token $a, Token $b): int {
		return $a->compareWeight($b);
	}
}
