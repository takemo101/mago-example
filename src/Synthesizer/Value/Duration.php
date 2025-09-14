<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * 期間（時間の長さ）を表すValueObject
 *
 * オーディオの再生時間や生成期間を秒単位で管理します。
 * 負の値は許可されません。
 */
final readonly class Duration
{
	/**
	 * 最小期間（秒）
	 * @var float
	 */
	public const float MIN = 0.0;

	/**
	 * 最大期間（秒）
	 * @var float
	 */
	public const float MAX = 3600.0; // 1 hour

	/**
	 * @param float $value 期間（秒）
	 * @throws InvalidArgumentException 期間が有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Duration must be between %.1f and %.1f seconds.', self::MIN, self::MAX));
		}
	}

	/**
	 * 秒から期間を作成します
	 *
	 * @param float $seconds 秒数
	 * @return self 期間
	 * @throws InvalidArgumentException 期間が有効範囲外の場合
	 */
	public static function fromSeconds(float $seconds): self
	{
		return new self($seconds);
	}

	/**
	 * ミリ秒から期間を作成します
	 *
	 * @param float $milliseconds ミリ秒
	 * @return self 期間
	 * @throws InvalidArgumentException 期間が有効範囲外の場合
	 */
	public static function fromMilliseconds(float $milliseconds): self
	{
		return new self($milliseconds / 1000.0);
	}

	/**
	 * 期間を秒で取得します
	 *
	 * @return float 秒数
	 */
	public function toSeconds(): float
	{
		return $this->value;
	}

	/**
	 * 期間をミリ秒で取得します
	 *
	 * @return float ミリ秒
	 */
	public function toMilliseconds(): float
	{
		return $this->value * 1000.0;
	}
}
