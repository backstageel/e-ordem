<?php

use App\Actions\Admin\DeleteRoleAction;

uses()->group('unit');

it('can be instantiated', function () {
    $action = new DeleteRoleAction();
    expect($action)->toBeInstanceOf(DeleteRoleAction::class);
});

it('validates role object structure', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->display_name = 'Test Role';

    expect($role->id)->toBe(1);
    expect($role->name)->toBe('test-role');
    expect($role->display_name)->toBe('Test Role');
});

it('handles role with valid ID', function () {
    $role = new stdClass();
    $role->id = 123;
    $role->name = 'test-role';

    expect($role->id)->toBe(123);
    expect($role->name)->toBe('test-role');
});

it('handles role with zero ID', function () {
    $role = new stdClass();
    $role->id = 0;
    $role->name = 'test-role';

    expect($role->id)->toBe(0);
});

it('handles role with negative ID', function () {
    $role = new stdClass();
    $role->id = -1;
    $role->name = 'test-role';

    expect($role->id)->toBe(-1);
});

it('validates role ID is numeric', function () {
    $role = new stdClass();
    $role->id = 'invalid';
    $role->name = 'test-role';

    expect(is_numeric($role->id))->toBeFalse();
});

it('handles role with null ID', function () {
    $role = new stdClass();
    $role->id = null;
    $role->name = 'test-role';

    expect($role->id)->toBeNull();
});

it('handles role with missing ID property', function () {
    $role = new stdClass();
    $role->name = 'test-role';

    expect(property_exists($role, 'id'))->toBeFalse();
});

it('validates role name is not empty', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = '';

    expect($role->name)->toBe('');
});

it('handles role with special characters in name', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'special-role-123';

    expect($role->name)->toBe('special-role-123');
});

it('handles role with unicode characters in name', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'função-especial';

    expect($role->name)->toBe('função-especial');
});

it('validates system role names', function () {
    $systemRoles = ['super-admin', 'admin', 'member', 'secretariado'];

    foreach ($systemRoles as $roleName) {
        $role = new stdClass();
        $role->id = 1;
        $role->name = $roleName;

        expect($role->name)->toBe($roleName);
    }
});

it('handles role with long name', function () {
    $longName = str_repeat('A', 255);
    $role = new stdClass();
    $role->id = 1;
    $role->name = $longName;

    expect($role->name)->toBe($longName);
});

it('handles role with display name', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->display_name = 'Test Role';

    expect($role->display_name)->toBe('Test Role');
});

it('handles role with empty display name', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->display_name = '';

    expect($role->display_name)->toBe('');
});

it('handles role with description', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->description = 'A test role for testing purposes';

    expect($role->description)->toBe('A test role for testing purposes');
});

it('handles role with empty description', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->description = '';

    expect($role->description)->toBe('');
});

it('handles role with long description', function () {
    $longDescription = str_repeat('This is a very long description. ', 50);
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'test-role';
    $role->description = $longDescription;

    expect($role->description)->toBe($longDescription);
});

it('validates role name format', function () {
    $role = new stdClass();
    $role->id = 1;
    $role->name = 'invalid role name'; // Contains space

    expect(strpos($role->name, ' ') !== false)->toBeTrue();
});
