<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

/**
 * 数学定数を管理するクラス
 *
 * オーディオ処理でよく使用される数学定数を提供します。
 */
final readonly class MathConstants
{
	/**
	 * π
	 * @var float
	 */
	public const float PI = M_PI;

	/**
	 * 2π（完全な円周）
	 * @var float
	 */
	public const float TWO_PI = 2.0 * M_PI;

	/**
	 * π/2（四分円）
	 * @var float
	 */
	public const float HALF_PI = M_PI / 2.0;

	/**
	 * 2πを返します
	 *
	 * @return float 2π
	 */
	public static function twoPi(): float
	{
		return self::TWO_PI;
	}

	/**
	 * π/2を返します
	 *
	 * @return float π/2
	 */
	public static function halfPi(): float
	{
		return self::HALF_PI;
	}

	/**
	 * 位相を0から2πの範囲に正規化します
	 *
	 * @param float $phase 位相（ラジアン）
	 * @return float 正規化された位相
	 */
	public static function normalizePhase(float $phase): float
	{
		return fmod($phase, self::TWO_PI);
	}

	/**
	 * 位相を正規化された値（0.0～1.0）に変換します
	 *
	 * @param float $phase 位相（ラジアン）
	 * @return float 正規化された値（0.0～1.0）
	 */
	public static function phaseToNormalized(float $phase): float
	{
		return $phase / self::TWO_PI;
	}
}
