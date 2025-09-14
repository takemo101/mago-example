<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

/**
 * フィルターの周波数応答を表すValueObject
 *
 * 特定の周波数に対するフィルターの減衰率を管理します。
 * 1.0は減衰なし、0.0は完全カットを表します。
 */
final readonly class FilterResponse
{
	/**
	 * @param float $attenuation 減衰率（0.0～1.0）
	 */
	public function __construct(
		public float $attenuation,
	) {}

	/**
	 * サンプルにフィルター応答を適用します
	 *
	 * @param float $sample 入力サンプル値
	 * @return float フィルタリングされたサンプル値
	 */
	public function apply(float $sample): float
	{
		return $sample * $this->attenuation;
	}

	/**
	 * 減衰なしのフィルター応答を返します
	 *
	 * @return self 減衰なしの応答
	 */
	public static function noAttenuation(): self
	{
		return new self(1.0);
	}
}
