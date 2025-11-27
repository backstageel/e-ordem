<?php

test('the application redirects to login when not authenticated', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
