<?php

namespace Takemo101\MagoExample\Synthesizer\Value;

use InvalidArgumentException;

/**
 * フィルターのカットオフ周波数を表すValueObject
 *
 * フィルターが作用し始める境界となる周波数を管理します。
 * この周波数を境に音の成分がフィルタリングされます。
 */
final readonly class Cutoff
{
    /** 最小カットオフ周波数（Hz） */
    public const float MIN = 20.0;

    /** 最大カットオフ周波数（Hz） */
    public const float MAX = 20000.0;

    /**
     * @param float $value カットオフ周波数（Hz）
     * @throws InvalidArgumentException カットオフ周波数が有効範囲外の場合
     */
    public function __construct(
        public float $value,
    ) {
        if ($value < self::MIN || $value > self::MAX) {
            throw new InvalidArgumentException(sprintf(
                'Cutoff frequency must be between %.1f and %.1f Hz.',
                self::MIN,
                self::MAX,
            ));
        }
    }

    /**
     * デフォルトのカットオフ周波数（最大値：フィルター無効状態）を返します
     *
     * @return self デフォルトカットオフ
     * @throws InvalidArgumentException カットオフ周波数が有効範囲外の場合
     */
    public static function default(): self
    {
        return new self(self::MAX);
    }
}
