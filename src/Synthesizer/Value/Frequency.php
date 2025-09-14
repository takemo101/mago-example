<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * オシレータの基本周波数を表すValueObject
 *
 * 音の高さを決定する基本的な周波数を管理します。
 * 人間が聞き取れる範囲（20Hz～20kHz）に制限されています。
 */
final readonly class Frequency
{
	/** 最小周波数（Hz） */
	public const float MIN = 20.0;

	/** 最大周波数（Hz） */
	public const float MAX = 20000.0;

	/**
	 * @param float $value 周波数値（Hz）
	 * @throws InvalidArgumentException 周波数が有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Frequency must be between %.1f and %.1f Hz.', self::MIN, self::MAX));
		}
	}

	/**
	 * デフォルトの周波数（A4: 440Hz）を返します
	 *
	 * @return self デフォルト周波数
	 * @throws InvalidArgumentException 周波数が有効範囲外の場合
	 */
	public static function default(): self
	{
		return new self(440.0);
	}
}
