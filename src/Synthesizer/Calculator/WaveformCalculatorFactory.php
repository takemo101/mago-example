<?php

namespace Takemo101\MagoExample\Synthesizer\Calculator;

use Takemo101\MagoExample\Synthesizer\Waveform;

final readonly class WaveformCalculatorFactory
{
	public static function create(Waveform $waveform): WaveformCalculatorInterface
	{
		return match ($waveform) {
			Waveform::SINE => new SineWaveCalculator(),
			Waveform::SQUARE => new SquareWaveCalculator(),
			Waveform::SAWTOOTH => new SawtoothWaveCalculator(),
			Waveform::TRIANGLE => new TriangleWaveCalculator(),
		};
	}
}
