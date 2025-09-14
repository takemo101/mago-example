<?php

namespace Takemo101\MagoExample\Synthesizer;

/**
 * 波形タイプの列挙型
 *
 * オシレータが生成可能な基本波形の種類を定義します。
 * それぞれ異なる音色特性を持ち、シンセサイザーの音作りの基礎となります。
 */
enum Waveform: string
{
	/** サイン波 - 純音に近い滑らかな音色 */
	case SINE = 'sine';

	/** 矩形波 - デジタル的で倍音豊富な音色 */
	case SQUARE = 'square';

	/** のこぎり波 - ブラス系の明るい音色 */
	case SAWTOOTH = 'sawtooth';

	/** 三角波 - サイン波とのこぎり波の中間的な音色 */
	case TRIANGLE = 'triangle';
}
