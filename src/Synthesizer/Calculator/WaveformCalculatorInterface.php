<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * 波形計算インターフェース
 *
 * 各種波形の計算ロジックを抽象化します。
 */
interface WaveformCalculatorInterface
{
	/**
	 * 指定された位相での波形値を計算します
	 *
	 * @param float $phase 位相（0.0～1.0の正規化された値）
	 * @return SampleValue 計算されたサンプル値
	 */
	public function calculate(float $phase): SampleValue;
}
