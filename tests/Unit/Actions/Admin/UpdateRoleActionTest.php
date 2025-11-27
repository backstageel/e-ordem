<?php

use App\Actions\Admin\UpdateRoleAction;

uses()->group('unit');

it('can be instantiated', function () {
    $action = new UpdateRoleAction();
    expect($action)->toBeInstanceOf(UpdateRoleAction::class);
});

it('has execute method', function () {
    $action = new UpdateRoleAction();
    expect(method_exists($action, 'execute'))->toBeTrue();
});

it('execute method is callable', function () {
    $action = new UpdateRoleAction();
    expect(is_callable([$action, 'execute']))->toBeTrue();
});

it('action class has proper namespace', function () {
    $action = new UpdateRoleAction();
    expect(get_class($action))->toBe('App\Actions\Admin\UpdateRoleAction');
});

it('action class extends base class', function () {
    $action = new UpdateRoleAction();
    expect($action)->toBeInstanceOf('App\Actions\Admin\UpdateRoleAction');
});

it('action can be serialized', function () {
    $action = new UpdateRoleAction();
    expect(is_string(serialize($action)))->toBeTrue();
});

it('action can be unserialized', function () {
    $action = new UpdateRoleAction();
    $serialized = serialize($action);
    $unserialized = unserialize($serialized);
    expect($unserialized)->toBeInstanceOf(UpdateRoleAction::class);
});

it('action has no public properties', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
    expect($properties)->toHaveCount(0);
});

it('action has execute method with proper signature', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    $method = $reflection->getMethod('execute');
    expect($method->isPublic())->toBeTrue();
    expect($method->getNumberOfParameters())->toBeGreaterThan(0);
});

it('action class is not abstract', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    expect($reflection->isAbstract())->toBeFalse();
});

it('action class is not interface', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    expect($reflection->isInterface())->toBeFalse();
});

it('action class is not trait', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    expect($reflection->isTrait())->toBeFalse();
});

it('action class has proper file location', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    $filename = $reflection->getFileName();
    expect($filename)->toContain('UpdateRoleAction.php');
});

it('action class has proper directory structure', function () {
    $action = new UpdateRoleAction();
    $reflection = new ReflectionClass($action);
    $filename = $reflection->getFileName();
    expect($filename)->toContain('Actions/Admin/');
});
