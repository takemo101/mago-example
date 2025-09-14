<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * サンプリングレートを表すValueObject
 *
 * 1秒間に何回サンプリングを行うかを表します。
 * 一般的な値は44100Hz（CD品質）、48000Hz（プロ用）などです。
 */
final readonly class SampleRate
{
	/**
	 * 最小サンプリングレート（Hz）
	 * @var int
	 */
	public const int MIN = 8000;

	/**
	 * 最大サンプリングレート（Hz）
	 * @var int
	 */
	public const int MAX = 192000;

	/**
	 * CD品質のサンプリングレート（Hz）
	 * @var int
	 */
	public const int CD_QUALITY = 44100;

	/**
	 * @param int $value サンプリングレート（Hz）
	 * @throws InvalidArgumentException サンプリングレートが有効範囲外の場合
	 */
	public function __construct(
		public int $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Sample rate must be between %d and %d Hz.', self::MIN, self::MAX));
		}
	}

	/**
	 * CD品質のサンプリングレート（44100Hz）を返します
	 *
	 * @return self CD品質のサンプリングレート
	 * @throws InvalidArgumentException サンプリングレートが有効範囲外の場合
	 */
	public static function cdQuality(): self
	{
		return new self(self::CD_QUALITY);
	}

	/**
	 * 指定した期間で必要なサンプル数を計算します
	 *
	 * @param Duration $duration 期間
	 * @return int サンプル数
	 */
	public function calculateSampleCount(Duration $duration): int
	{
		return (int) ($duration->value * $this->value);
	}

	/**
	 * サンプルインデックスから時間を計算します
	 *
	 * @param int $sampleIndex サンプルインデックス
	 * @return float 時間（秒）
	 */
	public function indexToTime(int $sampleIndex): float
	{
		return $sampleIndex / $this->value;
	}
}
