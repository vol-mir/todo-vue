<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SignupActivate;
use Carbon\Carbon;
use App\Models\User;
use File;
use Image;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Signup user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'activation_token' => Str::random(60)
        ]);
        $user->save();
        $user->notify(new SignupActivate($user));

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Signin user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only(['email', 'password']);
        $credentials['active'] = 1;
        $credentials['deleted_at'] = null;

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = Auth::user();
        $tokenResult  = $user->createToken(config('app.name'));
        $token = $tokenResult->token;

        if ($request->get('remember_me', false))
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
        //'token_type' => 'Bearer',
        //
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $request->user()->token()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [object] user
     */
    public function user(Request $request)
    {
        return response()->json([
            'message' => 'Successfully get the user',
            'user' => $request->user()
        ], 200);
    }

    /**
     * Signup activate user
     *
     * @param  [string] token
     * @return redirectTo to signin
     */
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], 404);
        }
        $user->active = true;
        $user->activation_token = '';
        $user->save();

        return response()->redirectTo("signin");
    }

    /**
     * Update the user in storage.
     *
     * @param  [object] request
     * @return [string] message
     * @return [object] user
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'string|unique:users,name,'.$user->id,
            'first_name' => 'string|nullable',
            'last_name' => 'string|nullable',
            'city' => 'string|nullable',
            'country' => 'string|nullable',
            'about_me' => 'string|nullable'
        ]);

        foreach($data as $key => $value) {
            $user->$key = $value;
        }
        $user->save();

        return response()->json([
            'message' => 'Successfully update user',
            'user' => $user
        ], 200);
    }

    /**
     * Update the avatar user in storage.
     *
     * @param  [object] request
     * @return [string] message
     * @return [object] avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();
        $oldAvatar = $user->avatar;

        if (Storage::disk('avatars')->exists($oldAvatar)) {
            Storage::disk('avatars')->delete($oldAvatar);
        }

        $imageName = null;

        if ($request->has('avatar') && $request->input('avatar')) {
            $image = $request->input('avatar'); // image base64 encoded
            preg_match("/data:image\/(.*?);/",$image,$image_extension); // extract the image extension
            $image = preg_replace('/data:image\/(.*?);base64,/','',$image); // remove the type part
            $image = str_replace(' ', '+', $image);
            $imageName = 'avatar_' . time() . '.' . $image_extension[1]; //generating unique file name;
            Storage::disk('avatars')->put($imageName,base64_decode($image));
        }

        $user->avatar = $imageName;
        $user->save();

        return response()->json([
            'message' => 'Successfully update avatar user',
            'user' => $user,
        ], 200);
    }

    /**
     * Update the user password in storage.
     *
     * @param  [object] request
     * @return [string] message
     * @return [object] user
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password' => 'required|string|confirmed'
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Successfully update user password',
            'user' => $user
        ], 200);
    }


}
