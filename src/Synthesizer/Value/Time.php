<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * エンベロープの時間パラメータを表すValueObject
 *
 * アタック、ディケイ、リリース時間など、
 * エンベロープの各段階にかかる時間を秒単位で管理します。
 */
final readonly class Time
{
	/** 最小時間（秒） */
	public const float MIN = 0.0;

	/** 最大時間（秒） */
	public const float MAX = 60.0; // 60 seconds max

	/**
	 * @param float $value 時間値（秒）
	 * @throws InvalidArgumentException 時間が有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Time must be between %.1f and %.1f seconds.', self::MIN, self::MAX));
		}
	}

	/**
	 * デフォルトの時間（0.1秒）を返します
	 *
	 * @return self デフォルト時間
	 * @throws InvalidArgumentException 時間が有効範囲外の場合
	 */
	public static function default(): self
	{
		return new self(0.1);
	}
}
