<?php

namespace Takemo101\MagoExample\Synthesizer;

use Takemo101\MagoExample\Synthesizer\Calculator\FilterCalculatorFactory;
use Takemo101\MagoExample\Synthesizer\Value\Cutoff;
use Takemo101\MagoExample\Synthesizer\Value\NormalizedFrequency;
use Takemo101\MagoExample\Synthesizer\Value\Resonance;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;
use InvalidArgumentException;

/**
 * フィルタークラス
 *
 * オーディオ信号の周波数成分を調整するフィルターを表現します。
 * ローパス、ハイパス、バンドパスの各種フィルタータイプに対応し、
 * カットオフ周波数とレゾナンスによって音色を変化させます。
 */
final readonly class Filter
{
    /**
     * @param FilterType $type フィルタータイプ
     * @param Cutoff $cutoff カットオフ周波数
     * @param Resonance $resonance レゾナンス（Q値）
     */
    public function __construct(
        public FilterType $type,
        public Cutoff $cutoff,
        public Resonance $resonance,
    ) {}

    /**
     * デフォルト設定のフィルターを生成します
     *
     * @return self デフォルトフィルター（ローパス、最大カットオフ、最小レゾナンス）
     * @throws InvalidArgumentException パラメータが有効範囲外の場合
     */
    public static function default(): self
    {
        return new self(
            type: FilterType::LOW_PASS,
            cutoff: Cutoff::default(),
            resonance: Resonance::default(),
        );
    }

    /**
     * 単一サンプルにフィルター処理を適用します
     *
     * @param float $sample 入力サンプル値
     * @param float $frequency 信号の周波数成分（Hz）
     * @return float フィルタリングされたサンプル値
     */
    public function apply(float $sample, float $frequency): float
    {
        $normalizedFreq = NormalizedFrequency::fromFrequencyAndCutoff($frequency, $this->cutoff);
        $calculator = FilterCalculatorFactory::create($this->type);
        $response = $calculator->calculate($normalizedFreq, $this->resonance);

        return $response->apply($sample);
    }

    /**
     * サンプル配列にフィルター処理を適用します
     *
     * @param array<SampleValue> $samples 入力サンプル配列
     * @param float $baseFrequency 信号の基本周波数（Hz）
     * @return array<SampleValue> フィルタリングされたサンプル配列
     */
    public function applySamples(array $samples, float $baseFrequency): array
    {
        return array_map(
            fn(SampleValue $sample): SampleValue => new SampleValue($this->apply($sample->value, $baseFrequency)),
            $samples,
        );
    }

    /**
     * カットオフ周波数を変調した新しいフィルターを作成します
     *
     * @param float $modulation 変調値（0.0～1.0）
     * @return self 変調されたフィルター
     * @throws InvalidArgumentException カットオフ周波数が有効範囲外の場合
     */
    public function modulateCutoff(float $modulation): self
    {
        $newCutoffValue = $this->cutoff->value * max(0.1, min(2.0, 1.0 + $modulation));
        $newCutoff = new Cutoff($newCutoffValue);

        return new self($this->type, $newCutoff, $this->resonance);
    }
}
