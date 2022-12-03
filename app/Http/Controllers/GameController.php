<?php

namespace App\Http\Controllers;

use App\Models\game;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\GameResource;
use App\Http\Requests\StoregameRequest;
use App\Http\Requests\UpdategameRequest;
use App\Models\statistic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{
    public function get_games_hot()
    {
        return GameResource::collection(game::where('trangthai', 1)->orderBy('soluotchoi','desc')->take(4)->get());
    }

    public function get_games_theloai($id_theloai)
    {   
        return GameResource::collection(game::where('id_theloai',$id_theloai)->where('trangthai', 1)->paginate(12));
    }

    public function get_games_search($keyword)
    {
        $keyword = str_replace('+',' ', $keyword);
        $keyword = '%' . $keyword . '%';
        return GameResource::collection(game::where('tengame','like',$keyword)->where('trangthai', 1)->paginate(12));
    }

    public function getGamesNew()
    {
        return GameResource::collection(game::where('trangthai', 1)->orderBy('created_at', 'desc')->limit(8)->get());
    }

    public function getGameRate()
    {
        return GameResource::collection(game::where('trangthai', 1)->orderBy('like', 'desc')->limit(8)->get());
    }
    public function getGamesPageSort($sort,$num)
    {
        return GameResource::collection(game::orderBy($sort)->paginate($num));
    }

    public function getGamesSortTheloai($id_theloai, $sort)
    {
        if ($sort == 'danhgia' )
        {
            if ($id_theloai == 0)
            {
                return GameResource::collection(game::where('trangthai', 1)->orderByRaw('(((games.like) / (games.like + games.unlike)) * 100) desc')->paginate(12));
            }
            else
            {
                return GameResource::collection(game::where('id_theloai',$id_theloai)->where('trangthai', 1)->orderByRaw('(((games.like) / (games.like + games.unlike)) * 100) desc')->paginate(12));
            }
        }
        else
        {
            if ($id_theloai == 0)
            {
                return GameResource::collection(game::where('trangthai', 1)->orderBy($sort,'asc')->paginate(12));
            }
            else
            {
                return GameResource::collection(game::where('id_theloai',$id_theloai)->where('trangthai', 1)->orderBy($sort,'asc')->paginate(12));
            }
        }
    }

    public function getGamesSearchSort($keyword, $sort)
    {
        $keyword = str_replace('+',' ', $keyword);
        $keyword = '%' . $keyword . '%';
        if ($sort == 'danhgia' )
        {
            return GameResource::collection(game::where('tengame', 'like', $keyword)->where('trangthai', 1)->orderByRaw('(((games.like) / (games.like + games.unlike)) * 100) desc')->paginate(12));
        }
        else
        {
            return GameResource::collection(game::where('tengame','like',$keyword)->where('trangthai', 1)->orderBy($sort,'asc')->paginate(12));
        }
        
    }

    public function getNumGame()
    {
        $count = game::select(DB::raw('count(*) AS num_game'))->get();
        return response(['num_game' => $count]);
    }

    public function getSumLuotchoi()
    {
        // $data = game::select(DB::raw('count(*) AS num_luotchoi'))
        $sum = game::select(DB::raw('sum(soluotchoi) AS sum'))->get();
        $thang = date('m');
        $nam = date('Y');
        $data = statistic::select('tongsoluotchoi', 'thang')->groupBy('nam')->groupBy('tongsoluotchoi')->groupBy('thang')->orderBy('nam', 'desc')->orderBy('thang', 'desc')->get();
        if ($thang != $data[0]->thang)
        { 
            $temp = 0;
            for ($i = 0; $i < count($data); $i++)
            {
                $temp = $data[$i]->tongsoluotchoi + $temp;
            }
            $true_data = $sum[0]->sum - $temp;
        }
        else
        {
            $temp = 0;
            for ($i = 1; $i < count($data); $i++)
            {
                $temp = $data[$i]->tongsoluotchoi + $temp;
            }
            $true_data = $sum[0]->sum - $temp;
        }
        statistic::updateOrCreate(
            ['thang' => $thang, 'nam' => $nam],
            ['tongsoluotchoi' => $true_data]);
    }

    public function getStatistic()
    {
        $data = statistic::select('tongsoluotchoi', DB::raw('CONCAT(thang, "/" , nam) AS date'))->groupBy('nam')->groupBy('tongsoluotchoi')->groupBy('thang')->orderBy('nam', 'desc')->orderBy('thang', 'desc')->limit(6)->get();
        return response([
            'data' => $data
        ]);
    }

    public function getGameBySlug($slug)
    {
        return new GameResource(game::where('slug', $slug)->first());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return GameResource::collection(game::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoregameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoregameRequest $request)
    {
        $data = $request->validated();
        $relativePath = $this->saveImage($data['thumb_image']);
        $data['thumb_image'] = $relativePath;
        $relativePath = $this->saveImage($data['image1']);
        $data['image1'] = $relativePath;
        $relativePath = $this->saveImage($data['image2']);
        $data['image2'] = $relativePath;
        $relativePath = $this->saveImage($data['image3']);
        $data['image3'] = $relativePath;
        $relativePath = $this->saveImage($data['image4']);
        $data['image4'] = $relativePath;
        $data['soluotchoi'] = 0;
        $data['like'] = 0;
        $data['unlike'] = 0;
        $game = game::create($data);
        return new GameResource($game);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(game $game)
    {
        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdategameRequest  $request
     * @param  \App\Models\game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(UpdategameRequest $request, game $game)
    {
        $data = $request->validated();
        if (isset($data['thumb_image']) && $data['thumb_image'] != $game->thumb_image)
        {
            $relativePath = $this->saveImage($data['thumb_image']);
            $data['thumb_image'] = $relativePath;
            if ($game->thumb_image)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->thumb_image;
                File::delete($absolutePath);
            }
        }
        if (isset($data['image1']) && $data['image1'] != $game->image1)
        {
            $relativePath = $this->saveImage($data['image1']);
            $data['image1'] = $relativePath;
            if ($game->image1)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->image1;
                File::delete($absolutePath);
            }
        }
        if (isset($data['image2']) && $data['image2'] != $game->image2)
        {
            $relativePath = $this->saveImage($data['image2']);
            $data['image2'] = $relativePath;
            if ($game->image2)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->image2;
                File::delete($absolutePath);
            }
        }
        if (isset($data['image3']) && $data['image3'] != $game->image3)
        {
            $relativePath = $this->saveImage($data['image3']);
            $data['image3'] = $relativePath;
            if ($game->image3)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->image3;
                File::delete($absolutePath);
            }
        }
        if (isset($data['image4']) && $data['image4'] != $game->image4)
        {
            $relativePath = $this->saveImage($data['image4']);
            $data['image4'] = $relativePath;
            if ($game->image4)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->image4;
                File::delete($absolutePath);
            }
        }
        $game->update($data);
        return new GameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(game $game)
    {
        if ($game->thumb_image)
        {
            $absolutePath = public_path('storage') . '/images/games/' . $game->thumb_image;
            File::delete($absolutePath);
        }
        if ($game->image1)
        {
            $absolutePath = public_path('storage') . '/images/games/' . $game->image1;
            File::delete($absolutePath);
        }
        if ($game->image2)
        {
            $absolutePath = public_path('storage') . '/images/games/' . $game->image2;
            File::delete($absolutePath);
        }
        if ($game->image3)
        {
            $absolutePath = public_path('storage') . '/images/games/' . $game->image3;
            File::delete($absolutePath);
        }
        if ($game->image4)
        {
            $absolutePath = public_path('storage') . '/images/games/' . $game->image4;
            File::delete($absolutePath);
        }
        $game->delete();
        return response('xoa thanh cong', 204);
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
        $dir = public_path('storage') . '/images/games/';
        if (!File::exists($dir))
        {
            File::makeDirectory($dir, 0755, true);
        }
        $fake_name_file = Str::random();
        $file = $fake_name_file . '.' . $type;
        $relativePath = $dir . $file;
        file_put_contents($relativePath, $image);

        return $file;
    }

    public function updateImage($data, $image, $game)
    {
        if (isset($data[$image]) && $data[$image] != $game->$image)
        {
            $relativePath = $this->saveImage($data[$image]);
            $data[$image] = $relativePath;

            if ($game->$image)
            {
                $absolutePath = public_path('storage') . '/images/games/' . $game->$image;
                File::delete($absolutePath);
            }
            return $relativePath;
        }
    }
}
