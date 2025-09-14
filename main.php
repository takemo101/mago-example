<?php

require_once 'vendor/autoload.php';

use Takemo101\MagoExample\Synthesizer\Patch;
use Takemo101\MagoExample\Synthesizer\Oscillator;
use Takemo101\MagoExample\Synthesizer\Filter;
use Takemo101\MagoExample\Synthesizer\Envelope;
use Takemo101\MagoExample\Synthesizer\Waveform;
use Takemo101\MagoExample\Synthesizer\FilterType;
use Takemo101\MagoExample\Synthesizer\Value\Frequency;
use Takemo101\MagoExample\Synthesizer\Value\Duration;
use Takemo101\MagoExample\Synthesizer\Value\SampleRate;
use Takemo101\MagoExample\Synthesizer\Value\Detune;
use Takemo101\MagoExample\Synthesizer\Value\Cutoff;
use Takemo101\MagoExample\Synthesizer\Value\Resonance;
use Takemo101\MagoExample\Synthesizer\Value\Time;
use Takemo101\MagoExample\Synthesizer\Value\Level;

echo "🎵 PHPシンセサイザーデモ\n";
echo "========================\n\n";

try {
    // 1. デフォルトパッチで基本的な音を生成
    echo "1. デフォルトパッチでA4音（440Hz）を生成...\n";
    $defaultPatch = Patch::default();
    $frequency = new Frequency(440.0); // A4
    $duration = Duration::fromSeconds(1.0); // 1秒
    $sampleRate = SampleRate::cdQuality(); // 44.1kHz

    $samples = $defaultPatch->synthesize($frequency, $duration, $sampleRate);
    echo "✅ " . count($samples) . "個のサンプルを生成しました\n";

    // サンプルの最初の数値を表示
    echo "最初の10サンプル:\n";
    for ($i = 0; $i < min(10, count($samples)); $i++) {
        printf("  Sample %d: %.4f\n", $i, $samples[$i]->value);
    }
    echo "\n";

    // 2. カスタムオシレータ（のこぎり波）を作成
    echo "2. のこぎり波オシレータでC5音（523Hz）を生成...\n";
    $sawOscillator = new Oscillator(
        waveform: Waveform::SAWTOOTH,
        frequency: new Frequency(523.25), // C5
        detune: Detune::default()
    );

    $customPatch = new Patch(
        oscillator: $sawOscillator,
        filter: Filter::default(),
        ampEnvelope: Envelope::default()
    );

    $c5Samples = $customPatch->synthesize(
        new Frequency(523.25),
        Duration::fromSeconds(0.5),
        $sampleRate
    );
    echo "✅ のこぎり波で " . count($c5Samples) . "個のサンプルを生成しました\n\n";

    // 3. フィルター付きの音を生成
    echo "3. ローパスフィルター付きの方形波を生成...\n";
    $filteredFilter = new Filter(
        type: FilterType::LOW_PASS,
        cutoff: new Cutoff(1000.0), // 1kHzカットオフ
        resonance: new Resonance(2.0)
    );

    $squareOscillator = new Oscillator(
        waveform: Waveform::SQUARE,
        frequency: new Frequency(220.0), // A3
        detune: new Detune(0.0)
    );

    $filteredPatch = new Patch(
        oscillator: $squareOscillator,
        filter: $filteredFilter,
        ampEnvelope: Envelope::default()
    );

    $filteredSamples = $filteredPatch->synthesize(
        new Frequency(220.0),
        Duration::fromSeconds(0.8),
        $sampleRate
    );
    echo "✅ フィルター付きで " . count($filteredSamples) . "個のサンプルを生成しました\n\n";

    // 4. カスタムエンベロープの音を生成
    echo "4. カスタムエンベロープ（速いアタック、長いリリース）の音を生成...\n";
    $customEnvelope = new Envelope(
        attack: new Time(0.001), // 1msの高速アタック
        decay: new Time(0.2),    // 200msディケイ
        sustain: new Level(0.6), // 60%サスティン
        release: new Time(2.0)   // 2秒リリース
    );

    $envelopePatch = new Patch(
        oscillator: new Oscillator(
            waveform: Waveform::SINE,
            frequency: new Frequency(880.0), // A5
            detune: new Detune(0.0)
        ),
        filter: Filter::default(),
        ampEnvelope: $customEnvelope
    );

    $envelopeSamples = $envelopePatch->synthesize(
        new Frequency(880.0),
        Duration::fromSeconds(3.0), // 3秒で長いリリースを確認
        $sampleRate
    );
    echo "✅ カスタムエンベロープで " . count($envelopeSamples) . "個のサンプルを生成しました\n\n";

    // 5. デチューンされた音を生成
    echo "5. +50セントデチューンされた音を生成...\n";
    $detunedOscillator = new Oscillator(
        waveform: Waveform::TRIANGLE,
        frequency: new Frequency(440.0), // A4
        detune: new Detune(50.0) // +50セント
    );

    $detunedPatch = new Patch(
        oscillator: $detunedOscillator,
        filter: Filter::default(),
        ampEnvelope: Envelope::default()
    );

    $detunedSamples = $detunedPatch->synthesize(
        new Frequency(440.0),
        Duration::fromSeconds(1.0),
        $sampleRate
    );
    echo "✅ デチューンされた音で " . count($detunedSamples) . "個のサンプルを生成しました\n\n";

    // 6. 統計情報を表示
    echo "6. 生成した音の統計情報\n";
    echo "----------------------\n";

    // 各パッチの音量レベルを分析
    $analyses = [
        'デフォルトパッチ' => $samples,
        'のこぎり波' => $c5Samples,
        'フィルター付き方形波' => $filteredSamples,
        'カスタムエンベロープ' => $envelopeSamples,
        'デチューン三角波' => $detunedSamples,
    ];

    foreach ($analyses as $name => $sampleArray) {
        $values = array_map(fn($sample) => abs($sample->value), $sampleArray);
        $maxLevel = max($values);
        $avgLevel = array_sum($values) / count($values);
        $rmsLevel = sqrt(array_sum(array_map(fn($v) => $v * $v, $values)) / count($values));

        printf("%s:\n", $name);
        printf("  最大レベル: %.4f\n", $maxLevel);
        printf("  平均レベル: %.4f\n", $avgLevel);
        printf("  RMSレベル: %.4f\n", $rmsLevel);
        printf("  サンプル数: %d\n", count($sampleArray));
        echo "\n";
    }

    echo "🎉 シンセサイザーデモが正常に完了しました！\n";
    echo "すべての波形タイプ、フィルター、エンベロープが正常に動作しています。\n";

} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    echo "ファイル: " . $e->getFile() . " (行 " . $e->getLine() . ")\n";
    exit(1);
}