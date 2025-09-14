<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\MathConstants;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * 三角波の計算を行うクラス
 *
 * サイン波とのこぎり波の中間的な音色を持つ三角波の値を位相から計算します。
 * サイン波よりも明るく、のこぎり波よりも滑らかな音色です。
 */
final readonly class TriangleWaveCalculator implements WaveformCalculatorInterface
{
	/**
	 * 三角波の値を計算します
	 *
	 * @param float $phase 位相（0.0～1.0の正規化された値）
	 * @return SampleValue 三角波のサンプル値
	 */
	#[\Override]
	public function calculate(float $phase): SampleValue
	{
		$sawValue = 2.0 * ($phase - floor(0.5 + $phase));
		$value = (2.0 * abs($sawValue)) - 1.0;
		return new SampleValue($value);
	}
}
