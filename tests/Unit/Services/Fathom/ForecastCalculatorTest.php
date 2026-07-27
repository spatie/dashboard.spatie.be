<?php

namespace Tests\Unit\Services\Fathom;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use App\Services\Fathom\ForecastCalculator;

class ForecastCalculatorTest extends TestCase
{
    private ForecastCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new ForecastCalculator();
    }

    public function testItForecastsUsingTheFourPrecedingMatchingWeekdays(): void
    {
        $now = CarbonImmutable::parse('2026-04-13 12:00:00', 'Europe/Brussels');
        $days = [
            ['date' => '2026-03-16', 'visits' => 80],
            ['date' => '2026-03-23', 'visits' => 100],
            ['date' => '2026-03-30', 'visits' => 120],
            ['date' => '2026-04-06', 'visits' => 100],
            ['date' => '2026-04-12', 'visits' => 999],
            ['date' => '2026-04-13', 'visits' => 40],
        ];

        $forecast = $this->calculator->calculate($days, $now, 'Europe/Brussels');

        $this->assertSame(90, $forecast['value']);
        $this->assertSame(100, $forecast['typical']);
        $this->assertSame(-10, $forecast['comparison_percent']);
        $this->assertEqualsWithDelta(0.5, $forecast['remaining_fraction'], 0.001);
    }

    public function testItFallsBackToAvailableCompleteDays(): void
    {
        $now = CarbonImmutable::parse('2026-04-13 18:00:00', 'Europe/Brussels');
        $days = [
            ['date' => '2026-04-11', 'visits' => 80],
            ['date' => '2026-04-12', 'visits' => 120],
            ['date' => '2026-04-13', 'visits' => 50],
        ];

        $forecast = $this->calculator->calculate($days, $now, 'Europe/Brussels');

        $this->assertSame(75, $forecast['value']);
        $this->assertSame(100, $forecast['typical']);
    }

    public function testItUsesTheCurrentCountWhenNoHistoryExists(): void
    {
        $forecast = $this->calculator->calculate(
            [['date' => '2026-04-13', 'visits' => 25]],
            CarbonImmutable::parse('2026-04-13 06:00:00', 'Europe/Brussels'),
            'Europe/Brussels',
        );

        $this->assertSame(25, $forecast['value']);
        $this->assertSame(25, $forecast['typical']);
        $this->assertSame(0, $forecast['comparison_percent']);
    }

    public function testItHandlesZeroTraffic(): void
    {
        $forecast = $this->calculator->calculate(
            [
                ['date' => '2026-04-06', 'visits' => 0],
                ['date' => '2026-04-13', 'visits' => 0],
            ],
            CarbonImmutable::parse('2026-04-13 12:00:00', 'Europe/Brussels'),
            'Europe/Brussels',
        );

        $this->assertSame(0, $forecast['value']);
        $this->assertSame(0, $forecast['typical']);
        $this->assertNull($forecast['comparison_percent']);
    }

    public function testItUsesTheActualLengthOfSpringDstDay(): void
    {
        $forecast = $this->calculator->calculate(
            [
                ['date' => '2026-03-22', 'visits' => 100],
                ['date' => '2026-03-29', 'visits' => 40],
            ],
            CarbonImmutable::parse('2026-03-29 12:00:00', 'Europe/Brussels'),
            'Europe/Brussels',
        );

        $this->assertEqualsWithDelta(12 / 23, $forecast['remaining_fraction'], 0.001);
        $this->assertSame(92, $forecast['value']);
    }

    public function testItUsesTheActualLengthOfAutumnDstDay(): void
    {
        $forecast = $this->calculator->calculate(
            [
                ['date' => '2026-10-18', 'visits' => 100],
                ['date' => '2026-10-25', 'visits' => 40],
            ],
            CarbonImmutable::parse('2026-10-25 12:00:00', 'Europe/Brussels'),
            'Europe/Brussels',
        );

        $this->assertEqualsWithDelta(12 / 25, $forecast['remaining_fraction'], 0.001);
        $this->assertSame(88, $forecast['value']);
    }
}
