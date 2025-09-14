<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

/**
 * デチューンが適用された実効周波数を表すValueObject
 *
 * 基本周波数にデチューン（音程の微調整）を適用した結果の周波数を管理します。
 * デチューンはセント単位で指定され、音楽的な音程調整に使用されます。
 */
final readonly class EffectiveFrequency
{
	/**
	 * @param float $value 実効周波数（Hz）
	 */
	public function __construct(
		public float $value,
	) {}

	/**
	 * 基本周波数とデチューンから実効周波数を計算します
	 *
	 * デチューンはセント単位で指定され、1200セント = 1オクターブです。
	 *
	 * @param Frequency $frequency 基本周波数
	 * @param Detune $detune デチューン値
	 * @return self 計算された実効周波数
	 */
	public static function fromFrequencyAndDetune(Frequency $frequency, Detune $detune): self
	{
		// デチューン比を計算: 2^(detune/1200)
		// pow()の代わりに指数計算を使用
		$detuneRatio = 2.0 ** ($detune->value / 1200.0);
		return new self($frequency->value * $detuneRatio);
	}

	/**
	 * 指定した時間における位相を生成します
	 *
	 * @param float $time 時間（秒）
	 * @return Phase 生成された位相
	 */
	public function createPhase(float $time): Phase
	{
		return Phase::fromTimeAndFrequency($time, $this->value);
	}
}
