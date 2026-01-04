<?php

namespace App\Http\Controllers\Settings;

use Storage;
use Illuminate\Http\Request;
use App\Concerns\HandleImageUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UserAvatarRequest;

class UserAvatarController extends Controller
{
    use HandleImageUpload;

    public function update(UserAvatarRequest $request)
    {
        $request->validated();

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $this->storeAndResizeImage(
                $request->file('avatar'),
            );

            $user->update([
                'avatar' => $user->avatar,
                'avatar_updated_at' => now(),
            ]);
        }

        return to_route('profile.edit');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update([
            'avatar' => null,
            'avatar_updated_at' => now(),
        ]);

        return to_route('profile.edit');
    }
}
