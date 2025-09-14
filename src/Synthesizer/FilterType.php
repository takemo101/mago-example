<?php

namespace Takemo101\MagoExample\Synthesizer;

/**
 * フィルタータイプの列挙型
 *
 * フィルターが作用する周波数特性の種類を定義します。
 * カットオフ周波数を境界として、異なる周波数帯域を処理します。
 */
enum FilterType: string
{
	/** ローパスフィルター - カットオフ周波数以下の成分を通す */
	case LOW_PASS = 'low_pass';

	/** ハイパスフィルター - カットオフ周波数以上の成分を通す */
	case HIGH_PASS = 'high_pass';

	/** バンドパスフィルター - カットオフ周波数周辺の成分のみを通す */
	case BAND_PASS = 'band_pass';
}
