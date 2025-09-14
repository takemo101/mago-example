<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\MathConstants;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * のこぎり波（サウトゥース波）の計算を行うクラス
 *
 * ブラス系の明るい音色を持つのこぎり波の値を位相から計算します。
 * 倍音成分が豊富で、シンセサイザーでよく使用される基本波形です。
 */
final readonly class SawtoothWaveCalculator implements WaveformCalculatorInterface
{
	/**
	 * のこぎり波の値を計算します
	 *
	 * @param float $phase 位相（0.0～1.0の正規化された値）
	 * @return SampleValue のこぎり波のサンプル値
	 */
	#[\Override]
	public function calculate(float $phase): SampleValue
	{
		$value = 2.0 * ($phase - floor(0.5 + $phase));
		return new SampleValue($value);
	}
}
