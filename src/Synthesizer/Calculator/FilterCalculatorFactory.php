<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\FilterType;

final readonly class FilterCalculatorFactory
{
	public static function create(FilterType $type): FilterCalculatorInterface
	{
		return match ($type) {
			FilterType::LOW_PASS => new LowPassFilterCalculator(),
			FilterType::HIGH_PASS => new HighPassFilterCalculator(),
			FilterType::BAND_PASS => new BandPassFilterCalculator(),
		};
	}
}
