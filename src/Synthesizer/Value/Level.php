<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * 音量レベルを表すValueObject
 *
 * エンベロープのサスティンレベルなど、音量の大きさを0.0～1.0の範囲で管理します。
 * 0.0は無音、1.0は最大音量を表します。
 */
final readonly class Level
{
	/** 最小レベル（無音） */
	public const float MIN = 0.0;

	/** 最大レベル（最大音量） */
	public const float MAX = 1.0;

	/**
	 * @param float $value レベル値（0.0～1.0）
	 * @throws InvalidArgumentException レベルが有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Level must be between %.1f and %.1f.', self::MIN, self::MAX));
		}
	}

	/**
	 * デフォルトのレベル（0.8）を返します
	 *
	 * @return self デフォルトレベル
	 * @throws InvalidArgumentException レベルが有効範囲外の場合
	 */
	public static function default(): self
	{
		return new self(0.8);
	}
}
