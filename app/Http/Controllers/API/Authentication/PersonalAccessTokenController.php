<?php

namespace App\Http\Controllers\API\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class PersonalAccessTokenController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function store(Request $request){

        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        return ['token' => $user->createToken($request->device_name, ['view:messages'])->plainTextToken];
    }


    public function destroy(Request $request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
