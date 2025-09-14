<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\FilterResponse;
use Takemo101\MagoExample\Synthesizer\Value\NormalizedFrequency;
use Takemo101\MagoExample\Synthesizer\Value\Resonance;

/**
 * ローパスフィルターの計算を行うクラス
 *
 * カットオフ周波数より高い周波数成分を減衰させるフィルターです。
 * 音を柔らかく、マイルドにする効果があります。
 */
final readonly class LowPassFilterCalculator implements FilterCalculatorInterface
{
	/**
	 * フィルターレスポンスを計算します
	 *
	 * @param NormalizedFrequency $normalizedFreq 正規化された周波数
	 * @param Resonance $resonance レゾナンス（共鳴）値
	 * @return FilterResponse フィルターレスポンス
	 */
	#[\Override]
	public function calculate(NormalizedFrequency $normalizedFreq, Resonance $resonance): FilterResponse
	{
		if ($normalizedFreq->value <= 1.0) {
			return FilterResponse::noAttenuation();
		}

		$distance = $normalizedFreq->value - 1.0;
		$attenuation = 1.0 / (1.0 + ($distance * $distance * $resonance->value));
		return new FilterResponse($attenuation);
	}
}
