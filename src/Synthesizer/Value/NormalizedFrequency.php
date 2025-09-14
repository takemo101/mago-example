<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

/**
 * カットオフ周波数で正規化された周波数を表すValueObject
 *
 * フィルター計算において、入力周波数をカットオフ周波数で割った値を管理します。
 * 1.0がカットオフ周波数に相当し、フィルターの応答計算に使用されます。
 */
final readonly class NormalizedFrequency
{
	/**
	 * @param float $value 正規化された周波数値
	 */
	public function __construct(
		public float $value,
	) {}

	/**
	 * 周波数とカットオフ周波数から正規化周波数を計算します
	 *
	 * @param float $frequency 対象周波数（Hz）
	 * @param Cutoff $cutoff カットオフ周波数
	 * @return self 正規化された周波数
	 */
	public static function fromFrequencyAndCutoff(float $frequency, Cutoff $cutoff): self
	{
		return new self($frequency / $cutoff->value);
	}
}
