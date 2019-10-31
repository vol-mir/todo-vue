<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\User;
use App\Models\PasswordReset;


class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);
        }
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['email' => $user->email, 'token' => str_random(60)]
        );
        if($user && $passwordReset){
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
        }
        return response()->json([
            'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if(!$passwordReset){
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        if(Carbon::parse($passwordReset->updated_at)->addMinute(720)->isPast()){
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ],404);
        }

        return response()->redirectTo("password/new/".$passwordReset['token']."?email=".$passwordReset['email']);
    }

    /**
     * Create password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if(!$passwordReset){
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if(!$user){
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);
        }


        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));

        return response()->json([
            'message' => 'You successfully create your new password.!'
        ]);
    }
}
