<?php

use App\View\Components\Admin\AlertCard;
use App\View\Components\Admin\ChartWidget;
use App\View\Components\Admin\StatCard;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
uses(RefreshDatabase::class);

it('can render stat card component', function () {
    $component = new StatCard(
        title: 'Total Users',
        value: 150,
        icon: 'fas fa-users',
        color: 'primary',
        growth: 12.5,
        growthLabel: 'This month'
    );

    expect($component->getFormattedValue())->toBe('150');
    expect($component->getGrowthBadgeClass())->toBe('bg-success-light text-success');
    expect($component->getGrowthIcon())->toBe('fa-arrow-up');
});

it('can render stat card with currency format', function () {
    $component = new StatCard(
        title: 'Revenue',
        value: 50000,
        icon: 'fas fa-dollar-sign',
        color: 'success',
        valueFormat: 'currency'
    );

    expect($component->getFormattedValue())->toBe('50.000 MT');
});

it('can render stat card with percentage format', function () {
    $component = new StatCard(
        title: 'Growth',
        value: 25,
        icon: 'fas fa-chart-line',
        color: 'info',
        valueFormat: 'percentage'
    );

    expect($component->getFormattedValue())->toBe('25%');
});

it('can render stat card with negative growth', function () {
    $component = new StatCard(
        title: 'Decline',
        value: 100,
        icon: 'fas fa-arrow-down',
        color: 'danger',
        growth: -5.2
    );

    expect($component->getGrowthBadgeClass())->toBe('bg-danger-light text-danger');
    expect($component->getGrowthIcon())->toBe('fa-arrow-down');
});

it('can render alert card component', function () {
    $component = new AlertCard(
        type: 'warning',
        title: 'System Alert',
        message: 'This is a warning message',
        icon: 'fas fa-exclamation-triangle',
        link: '/admin/alerts',
        linkText: 'View Details'
    );

    expect($component->getAlertClass())->toBe('alert-warning');
    expect($component->getIconClass())->toBe('fas fa-exclamation-triangle');
});

it('can render alert card with different types', function () {
    $successComponent = new AlertCard(
        type: 'success',
        title: 'Success',
        message: 'Operation completed',
        icon: 'fas fa-check'
    );

    expect($successComponent->getAlertClass())->toBe('alert-success');
    expect($successComponent->getIconClass())->toBe('fas fa-check-circle');

    $dangerComponent = new AlertCard(
        type: 'danger',
        title: 'Error',
        message: 'Something went wrong',
        icon: 'fas fa-times'
    );

    expect($dangerComponent->getAlertClass())->toBe('alert-danger');
    expect($dangerComponent->getIconClass())->toBe('fas fa-times-circle');
});

it('can render chart widget component', function () {
    $component = new ChartWidget(
        title: 'Sales Chart',
        chartId: 'salesChart',
        type: 'line',
        chartData: [
            'labels' => ['Jan', 'Feb', 'Mar'],
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => [10, 20, 30],
                    'borderColor' => '#007bff',
                ],
            ],
        ],
        period: 'This Year',
        link: '/admin/sales',
        linkText: 'View Sales'
    );

    expect($component->getChartHeight())->toBe('300px');
    expect($component->getDefaultOptions())->toHaveKey('responsive');
});

it('can render chart widget with different types', function () {
    $lineChart = new ChartWidget(
        title: 'Line Chart',
        chartId: 'lineChart',
        type: 'line',
        chartData: []
    );

    expect($lineChart->getChartHeight())->toBe('300px');

    $doughnutChart = new ChartWidget(
        title: 'Doughnut Chart',
        chartId: 'doughnutChart',
        type: 'doughnut',
        chartData: []
    );

    expect($doughnutChart->getChartHeight())->toBe('250px');
});

it('can get default chart options', function () {
    $component = new ChartWidget(
        title: 'Test Chart',
        chartId: 'testChart',
        type: 'line',
        chartData: []
    );

    $options = $component->getDefaultOptions();

    expect($options)->toHaveKey('responsive');
    expect($options)->toHaveKey('maintainAspectRatio');
    expect($options)->toHaveKey('plugins');
    expect($options['responsive'])->toBeTrue();
    expect($options['maintainAspectRatio'])->toBeFalse();
});

it('can get default chart options for doughnut chart', function () {
    $component = new ChartWidget(
        title: 'Test Chart',
        chartId: 'testChart',
        type: 'doughnut',
        chartData: []
    );

    $options = $component->getDefaultOptions();

    expect($options['plugins']['legend']['position'])->toBe('bottom');
    expect($options)->not->toHaveKey('scales');
});

it('can get default chart options for line chart with scales', function () {
    $component = new ChartWidget(
        title: 'Test Chart',
        chartId: 'testChart',
        type: 'line',
        chartData: []
    );

    $options = $component->getDefaultOptions();

    expect($options['plugins']['legend']['position'])->toBe('top');
    expect($options)->toHaveKey('scales');
    expect($options['scales']['y']['beginAtZero'])->toBeTrue();
});
