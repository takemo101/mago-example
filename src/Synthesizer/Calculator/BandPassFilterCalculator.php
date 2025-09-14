<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\FilterResponse;
use Takemo101\MagoExample\Synthesizer\Value\NormalizedFrequency;
use Takemo101\MagoExample\Synthesizer\Value\Resonance;

/**
 * バンドパスフィルターの計算を行うクラス
 *
 * カットオフ周波数周辺の帯域のみを通すフィルターです。
 * 特定の周波数帯域を強調し、他の帯域を減衰させる効果があります。
 */
final readonly class BandPassFilterCalculator implements FilterCalculatorInterface
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
		$distance = abs($normalizedFreq->value - 1.0);
		$resonantDistance = $distance * $resonance->value;
		$attenuation = 1.0 / (1.0 + ($resonantDistance * $resonantDistance));
		return new FilterResponse($attenuation);
	}
}
