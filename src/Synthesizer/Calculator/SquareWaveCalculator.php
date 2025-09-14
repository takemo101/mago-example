<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\MathConstants;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * 矩形波（スクエア波）の計算を行うクラス
 *
 * デジタル的で倍音成分が豊富な矩形波の値を位相から計算します。
 * クラシックなシンセサイザーサウンドで使用される基本的な波形です。
 */
final readonly class SquareWaveCalculator implements WaveformCalculatorInterface
{
	/**
	 * 矩形波の値を計算します
	 *
	 * @param float $phase 位相（0.0～1.0の正規化された値）
	 * @return SampleValue 矩形波のサンプル値
	 */
	#[\Override]
	public function calculate(float $phase): SampleValue
	{
		$value = $phase < 0.5 ? 1.0 : -1.0;
		return new SampleValue($value);
	}
}
