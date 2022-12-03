<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class OAuthController extends Controller
{
    public function redirect()
    {
        $url = Socialite::driver('facebook')->scopes(['public_profile','email'])->stateless()->redirect()->getTargetUrl();
        return response([
            'url' => $url
        ]);
    }

    public function handleCallback() 
    {
        try
        {
            $fb_user = Socialite::driver('facebook')->stateless()->user();
            $user = User::where('social_id',$fb_user->getId())->first();
            if (!$user)
            {
                $avatar_url = $fb_user->getAvatar() . '&access_token=' . $fb_user->token;
                $img = file_get_contents($avatar_url);
                $dir = 'public/images/profile/';
                $fake_name_file = Str::random();
                $file = $fake_name_file . '.jpg';
                $relativePath = $dir . $file;
                Storage::put($relativePath,$img);
                $user = User::create([
                    'email' => $fb_user->getEmail(),
                    'social_id' => $fb_user->getId(),
                    'avatar_url' => $file,
                    'name' => $fb_user->getName(),
                    'role' => 0
                ]);
                Auth::login($user);
                $token = $user->createToken('social')->plainTextToken;
            }
            else
            {
                Auth::login($user);
                $token = $user->createToken('social')->plainTextToken;
            }
            return view('callback', [
                'token' => $token,
                'user' => $user,
            ]);
            
        }
        catch (\Throwable $th)
        {
            return response('Đăng nhập không thành công ' . $th->getMessage(), 404);
        }
    }
}
