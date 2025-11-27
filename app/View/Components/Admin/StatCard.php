<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string|int $value,
        public string $icon,
        public string $color = 'primary',
        public ?float $growth = null,
        public ?string $growthLabel = null,
        public string $valueFormat = 'number'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.stat-card');
    }

    /**
     * Get the formatted value.
     */
    public function getFormattedValue(): string
    {
        return match ($this->valueFormat) {
            'currency' => number_format($this->value, 0, ',', '.').' MT',
            'percentage' => $this->value.'%',
            default => number_format($this->value),
        };
    }

    /**
     * Get the growth badge class.
     */
    public function getGrowthBadgeClass(): string
    {
        if ($this->growth === null) {
            return '';
        }

        return $this->growth >= 0 ? 'bg-success-light text-success' : 'bg-danger-light text-danger';
    }

    /**
     * Get the growth icon.
     */
    public function getGrowthIcon(): string
    {
        if ($this->growth === null) {
            return '';
        }

        return $this->growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
    }
}
