<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Value\Resonance;

/**
 * フィルタ計算の共通ユーティリティクラス
 *
 * 各種フィルタで使用される共通の数学的計算を提供します。
 */
final readonly class FilterCalculationUtils
{
	/**
	 * 距離とレゾナンスに基づく基本的な減衰計算を行います
	 *
	 * @param float $distance 基準からの距離
	 * @param Resonance $resonance レゾナンス値
	 * @return float 計算された減衰値（0.0～1.0）
	 */
	public static function calculateBasicAttenuation(float $distance, Resonance $resonance): float
	{
		return 1.0 / (1.0 + ($distance * $distance * $resonance->value));
	}

	/**
	 * 共鳴フィルタ用の減衰計算を行います
	 *
	 * @param float $distance 基準からの距離
	 * @param Resonance $resonance レゾナンス値
	 * @return float 計算された減衰値（0.0～1.0）
	 */
	public static function calculateResonantAttenuation(float $distance, Resonance $resonance): float
	{
		$resonantDistance = $distance * $resonance->value;
		return 1.0 / (1.0 + ($resonantDistance * $resonantDistance));
	}

	/**
	 * 正規化された周波数が範囲内にあるかチェックします
	 *
	 * @param float $normalizedFreq 正規化された周波数
	 * @param float $threshold しきい値
	 * @param string $comparison 比較演算子（'<=', '>=', '===', etc.）
	 * @return bool 範囲内の場合true
	 */
	public static function isFrequencyInRange(float $normalizedFreq, float $threshold, string $comparison): bool
	{
		return match ($comparison) {
			'<=' => $normalizedFreq <= $threshold,
			'>=' => $normalizedFreq >= $threshold,
			'<' => $normalizedFreq < $threshold,
			'>' => $normalizedFreq > $threshold,
			'===' => $normalizedFreq === $threshold,
			default => false,
		};
	}
}
