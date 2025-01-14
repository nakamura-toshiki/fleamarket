<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;

class CustomRegisteredUserController extends RegisteredUserController
{
    protected $guard;


    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @param  \Laravel\Fortify\Contracts\RegisterResponse  $registerResponse
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Create a new registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Fortify\Contracts\CreatesNewUsers  $creator
     * @return \Laravel\Fortify\Contracts\RegisterResponse
     */
    public function store(Request $request, CreatesNewUsers $creator): RegisterResponse
    {
        $registerRequest = new RegisterRequest();

        $validator = Validator::make(
            $request->all(),
            $registerRequest->rules(),
            $registerRequest->messages()
        );

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $validatedData = $validator->validated();

        if (config('fortify.lowercase_usernames')) {
            $validatedData[Fortify::username()] = Str::lower($validatedData[Fortify::username()]);
        }

        event(new Registered($user = $creator->create($validatedData)));

        $this->guard->login($user);

        return app(RegisterResponse::class);
    }

    public function redirectPath()
    {
        return '/email/verify';
    }
}

