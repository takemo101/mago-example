<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * 音程の微調整（デチューン）を表すValueObject
 *
 * セント単位での音程調整を管理します。
 * ±1200セント（1オクターブ）の範囲で音程を微調整できます。
 * 100セント = 1半音です。
 */
final readonly class Detune
{
	/** 最小デチューン値（セント） */
	public const float MIN = -1200.0;

	/** 最大デチューン値（セント） */
	public const float MAX = 1200.0;

	/**
	 * @param float $value デチューン値（セント）
	 * @throws InvalidArgumentException デチューン値が有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Detune must be between %.1f and %.1f cents.', self::MIN, self::MAX));
		}
	}

	/**
	 * デフォルトのデチューン（0セント：調整なし）を返します
	 *
	 * @return self デフォルトデチューン
	 * @throws InvalidArgumentException デチューン値が有効範囲外の場合
	 */
	public static function default(): self
	{
		return new self(0.0);
	}
}
