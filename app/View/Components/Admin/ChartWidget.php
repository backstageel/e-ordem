<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChartWidget extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string $chartId,
        public string $type = 'line',
        public array $chartData = [],
        public array $options = [],
        public ?string $period = null,
        public ?string $link = null,
        public string $linkText = 'Ver Detalhes'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.chart-widget');
    }

    /**
     * Get the chart height based on type.
     */
    public function getChartHeight(): string
    {
        return match ($this->type) {
            'doughnut', 'pie' => '250px',
            default => '300px',
        };
    }

    /**
     * Get the default options for the chart.
     */
    public function getDefaultOptions(): array
    {
        $defaultOptions = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => $this->type === 'doughnut' || $this->type === 'pie' ? 'bottom' : 'top',
                ],
            ],
        ];

        if ($this->type === 'line' || $this->type === 'bar') {
            $defaultOptions['scales'] = [
                'y' => [
                    'beginAtZero' => true,
                ],
            ];
        }

        return array_merge($defaultOptions, $this->options);
    }
}
