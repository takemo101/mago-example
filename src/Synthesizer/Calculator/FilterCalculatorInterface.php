<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\FilterResponse;
use Takemo101\MagoExample\Synthesizer\Value\NormalizedFrequency;
use Takemo101\MagoExample\Synthesizer\Value\Resonance;

/**
 * フィルター計算インターフェース
 *
 * 各種フィルタータイプ（ローパス、ハイパス等）の計算ロジックを抽象化します。
 */
interface FilterCalculatorInterface
{
	/**
	 * フィルターレスポンスを計算します
	 *
	 * @param NormalizedFrequency $normalizedFreq 正規化された周波数
	 * @param Resonance $resonance レゾナンス値
	 * @return FilterResponse フィルターレスポンス
	 */
	public function calculate(NormalizedFrequency $normalizedFreq, Resonance $resonance): FilterResponse;
}
