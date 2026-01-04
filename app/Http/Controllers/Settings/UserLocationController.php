<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UserLocationRequest;

class UserLocationController extends Controller
{
    public function store(UserLocationRequest $request)
    {
        $request->validated();

        $request->user()->update([
            'location' => $request->location,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->user()->update([
            'location' => null,
            'location_visibility' => false,
        ]);
    }
}
