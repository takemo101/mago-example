<?php

namespace Takemo101\MagoExample\Synthesizer;

use Takemo101\MagoExample\Synthesizer\Calculator\WaveformCalculatorFactory;
use Takemo101\MagoExample\Synthesizer\Value\Detune;
use Takemo101\MagoExample\Synthesizer\Value\Duration;
use Takemo101\MagoExample\Synthesizer\Value\EffectiveFrequency;
use Takemo101\MagoExample\Synthesizer\Value\Frequency;
use Takemo101\MagoExample\Synthesizer\Value\SampleRate;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;
use InvalidArgumentException;

/**
 * オシレータ（発振器）クラス
 *
 * シンセサイザーの音源となる基本波形を生成します。
 * 周波数、波形タイプ、デチューンパラメータを持ち、
 * 時間軸に沿った波形データを出力する責任を持ちます。
 */
final readonly class Oscillator
{
    /**
     * @param Waveform $waveform 波形タイプ
     * @param Frequency $frequency 基本周波数
     * @param Detune $detune デチューン値
     */
    public function __construct(
        public Waveform $waveform,
        public Frequency $frequency,
        public Detune $detune,
    ) {}

    /**
     * デフォルト設定のオシレータを生成します
     *
     * @return self デフォルトオシレータ（サイン波、440Hz、デチューンなし）
     */
    public static function default(): self
    {
        return new self(
            waveform: Waveform::SINE,
            frequency: Frequency::default(),
            detune: Detune::default(),
        );
    }

    /**
     * 指定した時間における波形のサンプル値を生成します
     *
     * @param float $time 時間（秒）
     * @return SampleValue サンプル値
     * @throws InvalidArgumentException サンプル値が有効範囲外の場合
     */
    public function generate(float $time): SampleValue
    {
        $effectiveFrequency = EffectiveFrequency::fromFrequencyAndDetune($this->frequency, $this->detune);
        $phase = $effectiveFrequency->createPhase($time);
        $calculator = WaveformCalculatorFactory::create($this->waveform);

        return $calculator->calculate($phase->toNormalizedValue());
    }

    /**
     * 指定した期間の複数のサンプルを生成します
     *
     * @param Duration $duration 生成期間
     * @param SampleRate|null $sampleRate サンプリングレート
     * @return array<SampleValue> サンプル値の配列
     * @throws InvalidArgumentException パラメータが有効範囲外の場合
     */
    public function generateSamples(Duration $duration, null|SampleRate $sampleRate = null): array
    {
        $sampleRate ??= SampleRate::cdQuality();
        $samples = [];
        $numSamples = $sampleRate->calculateSampleCount($duration);

        for ($i = 0; $i < $numSamples; $i++) {
            $time = $sampleRate->indexToTime($i);
            $samples[] = $this->generate($time);
        }

        return $samples;
    }

    /**
     * 指定した周波数で指定した期間のサンプルを生成します
     *
     * @param Frequency $frequency 生成周波数
     * @param Duration $duration 生成期間
     * @param SampleRate|null $sampleRate サンプリングレート
     * @return array<SampleValue> サンプル値の配列
     */
    public function generateSamplesWithFrequency(
        Frequency $frequency,
        Duration $duration,
        null|SampleRate $sampleRate = null,
    ): array {
        $tempOscillator = new self($this->waveform, $frequency, $this->detune);
        return $tempOscillator->generateSamples($duration, $sampleRate);
    }
}
