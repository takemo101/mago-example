<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

/**
 * 波形生成における位相を表すValueObject
 *
 * オシレータが波形を生成する際の位相（フェーズ）を管理します。
 * 位相は時間と周波数から計算され、波形の形状を決定する基本的な要素です。
 */
final readonly class Phase
{
	/**
	 * @param float $value 位相値（ラジアン）
	 */
	public function __construct(
		public float $value,
	) {}

	/**
	 * 時間と周波数から位相を生成します
	 *
	 * @param float $time 時間（秒）
	 * @param float $frequency 周波数（Hz）
	 * @return self 計算された位相
	 */
	public static function fromTimeAndFrequency(float $time, float $frequency): self
	{
		return new self(2.0 * M_PI * $frequency * $time);
	}

	/**
	 * 位相値を0から2πの範囲に正規化します
	 *
	 * @return float 正規化された位相値（0 <= value < 2π）
	 */
	public function normalize(): float
	{
		return fmod($this->value, 2.0 * M_PI);
	}

	/**
	 * 位相値を0.0から1.0の範囲に正規化します
	 *
	 * @return float 正規化された位相値（0.0 <= value < 1.0）
	 */
	public function toNormalizedValue(): float
	{
		return $this->normalize() / (2.0 * M_PI);
	}
}
