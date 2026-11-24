<?php

use Illuminate\Support\Facades\Storage;

it('sets seeded account creation to the first seeded order date', function () {
    $this->travelTo('2026-11-24 12:00:00');
    Storage::fake('public');

    $this->seed();

    $this->assertDatabaseHas('users', [
        'email' => 'sebastian@epelican.com',
        'created_at' => '2026-09-25 12:00:00',
    ]);
});
