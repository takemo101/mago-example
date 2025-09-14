<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * フィルターのレゾナンス（Q値）を表すValueObject
 *
 * フィルターのカットオフ周波数周辺での強調の強さを管理します。
 * 値が大きいほどカットオフ周波数付近の成分が強調され、
 * フィルターの効果がより鋭くなります。
 */
final readonly class Resonance
{
	/** 最小レゾナンス値 */
	public const float MIN = 1.0;

	/** 最大レゾナンス値 */
	public const float MAX = 20.0;

	/**
	 * @param float $value レゾナンス値（Q値）
	 * @throws InvalidArgumentException レゾナンスが有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Resonance must be between %.1f and %.1f.', self::MIN, self::MAX));
		}
	}

	/**
	 * デフォルトのレゾナンス（最小値：レゾナンス無効状態）を返します
	 *
	 * @return self デフォルトレゾナンス
	 * @throws InvalidArgumentException レゾナンスが有効範囲外の場合
	 */
	public static function default(): self
	{
		return new self(self::MIN);
	}
}
