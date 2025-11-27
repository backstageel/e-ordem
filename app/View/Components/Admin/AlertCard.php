<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlertCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public string $title,
        public string $message,
        public string $icon,
        public ?string $link = null,
        public ?string $linkText = null,
        public bool $dismissible = true
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.alert-card');
    }

    /**
     * Get the alert class based on type.
     */
    public function getAlertClass(): string
    {
        return match ($this->type) {
            'success' => 'alert-success',
            'warning' => 'alert-warning',
            'danger' => 'alert-danger',
            'info' => 'alert-info',
            default => 'alert-primary',
        };
    }

    /**
     * Get the icon class based on type.
     */
    public function getIconClass(): string
    {
        return match ($this->type) {
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'danger' => 'fas fa-times-circle',
            'info' => 'fas fa-info-circle',
            default => 'fas fa-bell',
        };
    }
}
