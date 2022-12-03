<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\like;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getUserPageSort($sort,$num)
    {
        return UserResource::collection(user::orderBy($sort, 'asc')->paginate($num));
    }

    public function getNumUser()
    {
        $count = User::select(DB::raw('count(*) AS num_user'))->get();
        return response(['num_user' => $count]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();  
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['avatar_url']) && $data['avatar_url'] != $user->avatar_url)
        {
            $relativePath = $this->saveImage($data['avatar_url']);
            $data['avatar_url'] = $relativePath;

            if ($user->avatar_url)
            {
                $absolutePath = public_path('storage') . '/images/profile/' . $user->avatar_url;
                File::delete($absolutePath);
            }
        }
        $user->update($data);        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response('success', 200);
    }

    public function saveImage($image)
    {
        if (preg_match('/data:image\/(\w+);base64,/', $image, $type)) 
        {
            $image = substr($image, strpos($image, ',') + 1);
            $type = strtolower($type[1]);
            if (!in_array($type, ['jpg','png','jpeg']))
            {
                throw new \Exception('file khong dung dinh dang');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);
            if ($image === false)
            {
                throw new \Exception('base64_decode loi');
            }
        }
        else 
        {
            throw new \Exception('du lieu anh bi sai');
        }
        $dir = public_path('storage') . '/images/profile/';
        $fake_name_file = Str::random();
        $file = $fake_name_file . '.jpg';
        $relativePath = $dir . $file;
        file_put_contents($relativePath, $image);

        return $file;
    }

    public function likeGame($game_id)
    {
        $user_id = Auth::user()->id;
        $liked = like::create([
            'user_id' => $user_id,
            'game_id' => $game_id
        ]);
        if (!$liked) {
            return response('Yêu thích không thành công', 422);
        }
        return response('okela',200);
    }

    public function unlikeGame($game_id)
    {
        $user_id = Auth::user()->id;
        like::where('user_id',$user_id)->where('game_id', $game_id)->delete();
        return response('okela',200);
    }
}
