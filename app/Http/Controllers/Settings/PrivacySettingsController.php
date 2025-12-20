<?php

namespace App\Http\Controllers\Settings;

use App\Enums\Privacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rules\Enum;

class PrivacySettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'visibility' => ['required', new Enum(Privacy::class)],
        ]);

        $user = $request->user();

        $user->update([
           'activities_visibility' => $request->visibility,
        ]);
    }
}
