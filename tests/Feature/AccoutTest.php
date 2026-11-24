<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

it('lets users upload and remove a profile picture', function () {
    Storage::fake('public');
    $userId = createUser();
    loginAs($userId);

    $this->post(route('account.avatar.update'), ['avatar' => UploadedFile::fake()->image('me.png', 64, 64)])
        ->assertSessionHasNoErrors();

    $path = DB::table('users')->where('user_id', $userId)->value('avatar_url');
    expect($path)->toStartWith('avatars/');
    Storage::disk('public')->assertExists($path);

    $this->delete(route('account.avatar.destroy'));

    expect(DB::table('users')->where('user_id', $userId)->value('avatar_url'))->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
