<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function getArticlePageSort($sort,$num)
    {
        return ArticleResource::collection(Article::orderBy($sort, 'asc')->paginate($num));
    }

    public function getPopularArticle()
    {
        return ArticleResource::collection(Article::orderBy('rate', 'desc')->limit(4)->get());
    }

    public function getArticleRelate()
    {
        return ArticleResource::collection(Article::inRandomOrder()->limit(4)->get());
    }

    public function getArticleView()
    {
        return ArticleResource::collection(Article::orderBy('luotxem', 'desc')->limit(4)->get());
    }

    public function getArticleNew()
    {
        return ArticleResource::collection(Article::orderBy('created_at', 'desc')->paginate(8));
    }

    public function getArticleBySlug($slug)
    {
        return new ArticleResource(Article::where('slug', $slug)->first());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = Article::select(DB::raw('count(*) as count'))->get();
        return response(['count' => $count]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $data = $request->validated();
        $data['thumb_image'] = $this->saveImage($data['thumb_image']);
        Article::create($data);
        return response('success', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $data = $request->validated();
        if (isset($data['thumb_image']) && $data['thumb_image'] != $article->thumb_image)
        {
            $relativePath = $this->saveImage($data['thumb_image']);
            $data['thumb_image'] = $relativePath;
            if ($article->thumb_image)
            {
                $absolutePath = public_path('/images/articles/') . $article->thumb_image;
                File::delete($absolutePath);
            }
        }
        $article->update($data);
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if ($article->thumb_image)
        {
            $absolutePath = public_path('/images/articles/') . $article->thumb_image;
            File::delete($absolutePath);
        }
        $article->delete();
        return response('okela', 200);
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload'))
        {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('/images/articles/'), $fileName);
            $url = asset('/images/articles/' . $fileName);

            return response([
                'fileName' => $fileName,
                'uploaded' => 1,
                'url' => $url
            ]);
        }
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
        $dir = public_path('/images/articles/');
        $fake_name_file = Str::random();
        $file = $fake_name_file . '.jpg';
        $relativePath = $dir . $file;
        file_put_contents($relativePath, $image);

        return $file;
    }
}
