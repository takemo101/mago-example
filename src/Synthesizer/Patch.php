<?php

namespace Takemo101\MagoExample\Synthesizer;

use InvalidArgumentException;
use Takemo101\MagoExample\Synthesizer\Value\Duration;
use Takemo101\MagoExample\Synthesizer\Value\Frequency;
use Takemo101\MagoExample\Synthesizer\Value\SampleRate;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;

/**
 * シンセサイザーパッチクラス
 *
 * オシレーター、フィルター、エンベロープを組み合わせて
 * 統合的な音声合成を行うパッチです。
 */
final readonly class Patch
{
	public function __construct(
		public Oscillator $oscillator,
		public Filter $filter,
		public Envelope $ampEnvelope,
		public null|Envelope $filterEnvelope = null,
	) {}

	/**
	 * デフォルトパッチを作成します
	 *
	 * @return self デフォルト設定のパッチ
	 */
	public static function default(): self
	{
		return new self(
			oscillator: Oscillator::default(),
			filter: Filter::default(),
			ampEnvelope: Envelope::default(),
		);
	}

	/**
	 * 統合音声合成を実行します
	 *
	 * オシレーター、フィルター、エンベロープを順次適用して
	 * 最終的な音声サンプルを生成します。
	 *
	 * @param Frequency $frequency 生成する音の周波数
	 * @param Duration $duration 生成する音の長さ
	 * @param SampleRate $sampleRate サンプルレート
	 * @return array<SampleValue> 生成されたオーディオサンプルの配列
	 * @throws InvalidArgumentException サンプル値が有効範囲外の場合
	 */
	public function synthesize(Frequency $frequency, Duration $duration, SampleRate $sampleRate): array
	{
		// 1. オシレーターで基本波形を生成
		$rawSamples = $this->oscillator->generateSamplesWithFrequency($frequency, $duration, $sampleRate);

		// 2. フィルターエンベロープが設定されている場合、フィルターパラメータを時間変化
		if ($this->filterEnvelope !== null) {
			$filteredSamples = [];
			foreach ($rawSamples as $index => $sample) {
				$time = ((float) $index / count($rawSamples)) * $duration->toSeconds();
				$envelopeLevel = $this->filterEnvelope->getLevelAt($time);

				// エンベロープレベルに応じてカットオフを変調
				$modulatedFilter = $this->filter->modulateCutoff($envelopeLevel->value);
				$filteredValue = $modulatedFilter->apply($sample->value, $frequency->value);
				$filteredSamples[] = new SampleValue($filteredValue);
			}

			// アンプエンベロープを適用
			$finalSamples = [];
			foreach ($filteredSamples as $index => $sample) {
				$time = ((float) $index / count($filteredSamples)) * $duration->toSeconds();
				$envelopeLevel = $this->ampEnvelope->getLevelAt($time);
				$finalSample = new SampleValue($sample->value * $envelopeLevel->value);
				$finalSamples[] = $finalSample;
			}

			return $finalSamples;
		}

		// 3. フィルターを適用
		$filteredSamples = $this->filter->applySamples($rawSamples, $frequency->value);

		// 4. アンプエンベロープを適用
		$finalSamples = [];
		foreach ($filteredSamples as $index => $sample) {
			$time = ((float) $index / count($filteredSamples)) * $duration->toSeconds();
			$envelopeLevel = $this->ampEnvelope->getLevelAt($time);
			$finalSample = new SampleValue($sample->value * $envelopeLevel->value);
			$finalSamples[] = $finalSample;
		}

		return $finalSamples;
	}
}
