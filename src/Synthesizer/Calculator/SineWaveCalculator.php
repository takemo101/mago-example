<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\MathConstants;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * サイン波（正弦波）の計算を行うクラス
 *
 * 最も基本的な波形であるサイン波の値を位相から計算します。
 * 滑らかで倍音成分が少ない、純音に近い音色を生成します。
 */
final readonly class SineWaveCalculator implements WaveformCalculatorInterface
{
	/**
	 * サイン波の値を計算します
	 *
	 * @param float $phase 位相（0.0～1.0の正規化された値）
	 * @return SampleValue サイン波のサンプル値
	 */
	#[\Override]
	public function calculate(float $phase): SampleValue
	{
		$radians = $phase * MathConstants::TWO_PI;
		return new SampleValue(sin($radians));
	}
}
