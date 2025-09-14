<?php

namespace Takemo101\MagoExample\Synthesizer;

use Takemo101\MagoExample\Synthesizer\Value\Level;
use Takemo101\MagoExample\Synthesizer\Value\SampleValue;
use Takemo101\MagoExample\Synthesizer\Value\Time;
use InvalidArgumentException;

/**
 * ADSR エンベロープクラス
 *
 * Attack, Decay, Sustain, Release の4段階で音量変化を制御します。
 * 時間経過に応じたレベル変化を計算する責任を持ちます。
 */
final readonly class Envelope
{
    /**
     * @param Time $attack アタック時間
     * @param Time $decay ディケイ時間
     * @param Level $sustain サスティンレベル
     * @param Time $release リリース時間
     */
    public function __construct(
        public Time $attack,
        public Time $decay,
        public Level $sustain,
        public Time $release,
    ) {}

    /**
     * デフォルト設定のエンベロープを生成します
     *
     * @return self デフォルトエンベロープ
     * @throws InvalidArgumentException パラメータが有効範囲外の場合
     */
    public static function default(): self
    {
        return new self(
            attack: new Time(0.01),
            decay: new Time(0.1),
            sustain: new Level(0.8),
            release: new Time(0.5),
        );
    }

    /**
     * 指定時間におけるエンベロープのレベルを計算します
     *
     * @param float $time 経過時間（秒）
     * @param float|null $releaseTime リリース開始時間（nullの場合はリリースしない）
     * @return Level エンベロープレベル
     */
    public function getLevelAt(float $time, null|float $releaseTime = null): Level
    {
        // リリースフェーズの処理
        if ($releaseTime !== null && $time >= $releaseTime) {
            return $this->calculateReleaseLevel($time - $releaseTime);
        }

        // アタックフェーズ
        if ($time <= $this->attack->value) {
            return $this->calculateAttackLevel($time);
        }

        // ディケイフェーズ
        $decayEndTime = $this->attack->value + $this->decay->value;
        if ($time <= $decayEndTime) {
            return $this->calculateDecayLevel($time - $this->attack->value);
        }

        // サスティンフェーズ
        return $this->sustain;
    }

    /**
     * サンプルにエンベロープを適用します
     *
     * @param SampleValue $sample 入力サンプル
     * @param float $time 経過時間（秒）
     * @param float|null $releaseTime リリース開始時間
     * @return SampleValue エンベロープ適用後のサンプル
     * @throws InvalidArgumentException サンプル値が有効範囲外の場合
     */
    public function apply(SampleValue $sample, float $time, null|float $releaseTime = null): SampleValue
    {
        $level = $this->getLevelAt($time, $releaseTime);
        return $sample->multiplyScalar($level->value);
    }

    /**
     * アタックフェーズのレベルを計算します
     *
     * @param float $time アタック開始からの経過時間
     * @return Level レベル
     * @throws InvalidArgumentException レベルが有効範囲外の場合
     */
    private function calculateAttackLevel(float $time): Level
    {
        if ($this->attack->value <= 0.0) {
            return new Level(1.0);
        }

        $progress = $time / $this->attack->value;
        return new Level(min(1.0, $progress));
    }

    /**
     * ディケイフェーズのレベルを計算します
     *
     * @param float $time ディケイ開始からの経過時間
     * @return Level レベル
     * @throws InvalidArgumentException レベルが有効範囲外の場合
     */
    private function calculateDecayLevel(float $time): Level
    {
        if ($this->decay->value <= 0.0) {
            return $this->sustain;
        }

        $progress = $time / $this->decay->value;
        $progress = min(1.0, $progress);

        // 1.0からサスティンレベルへの線形補間
        $currentLevel = 1.0 - ($progress * (1.0 - $this->sustain->value));
        return new Level($currentLevel);
    }

    /**
     * リリースフェーズのレベルを計算します
     *
     * @param float $time リリース開始からの経過時間
     * @return Level レベル
     * @throws InvalidArgumentException レベルが有効範囲外の場合
     */
    private function calculateReleaseLevel(float $time): Level
    {
        if ($this->release->value <= 0.0) {
            return new Level(0.0);
        }

        $progress = $time / $this->release->value;
        if ($progress >= 1.0) {
            return new Level(0.0);
        }

        // サスティンレベルから0への線形補間
        $currentLevel = $this->sustain->value * (1.0 - $progress);
        return new Level($currentLevel);
    }
}
