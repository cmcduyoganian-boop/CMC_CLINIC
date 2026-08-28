<?php

use Illuminate\Support\Facades\Route;

test('form pages routes are registered', function () {
    expect(Route::has('forms.consent'))->toBeTrue()
        ->and(Route::has('forms.student-info'))->toBeTrue();
});
