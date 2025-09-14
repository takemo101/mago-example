<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * オーディオサンプル値を表すValueObject
 *
 * デジタルオーディオにおける個々のサンプル値を管理します。
 * -1.0から1.0の範囲で正規化された値を保持します。
 */
final readonly class SampleValue
{
	/**
	 * 最小サンプル値
	 * @var float
	 */
	public const float MIN = -1.0;

	/**
	 * 最大サンプル値
	 * @var float
	 */
	public const float MAX = 1.0;

	/**
	 * @param float $value サンプル値（-1.0～1.0）
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public function __construct(
		public float $value,
	) {
		if ($value < self::MIN || $value > self::MAX) {
			throw new InvalidArgumentException(sprintf('Sample value must be between %.1f and %.1f.', self::MIN, self::MAX));
		}
	}

	/**
	 * 無音のサンプル値を返します
	 *
	 * @return self 無音サンプル
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public static function silence(): self
	{
		return new self(0.0);
	}

	/**
	 * 最大正のサンプル値を返します
	 *
	 * @return self 最大正サンプル
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public static function maxPositive(): self
	{
		return new self(self::MAX);
	}

	/**
	 * 最大負のサンプル値を返します
	 *
	 * @return self 最大負サンプル
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public static function maxNegative(): self
	{
		return new self(self::MIN);
	}

	/**
	 * 別のサンプル値を掛け合わせます
	 *
	 * @param SampleValue $other 掛ける値
	 * @return self 計算結果
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public function multiply(SampleValue $other): self
	{
		return new self($this->value * $other->value);
	}

	/**
	 * スカラー値を掛け合わせます
	 *
	 * @param float $scalar スカラー値
	 * @return self 計算結果
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public function multiplyScalar(float $scalar): self
	{
		$result = $this->value * $scalar;
		// クランプして範囲内に収める
		$result = max(self::MIN, min(self::MAX, $result));
		return new self($result);
	}
}
