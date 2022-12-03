<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TheLoaiGameController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/get_comments/{game_id}/{num}', [CommentController::class, 'getComments']);
Route::get('/user_page_sort/{sort}/{num}', [UserController::class, 'getUserPageSort']);
Route::get('/num_user', [UserController::class, 'getNumUser']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/changepassword', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/likegame/{game_id}', [UserController::class, 'likeGame']);
    Route::post('/unlikegame/{game_id}', [UserController::class, 'unlikeGame']);
    Route::resource('comment', CommentController::class);
    Route::resource('user', UserController::class);
});

Route::get('/games_hot', [GameController::class, 'get_games_hot']);
Route::get('/games_theloai/{id_theloai}', [GameController::class, 'get_games_theloai']);
Route::get('/game_search/{keyword}', [GameController::class, 'get_games_search']);
Route::get('/games_luotchoi/{id}', [GameController::class, 'get_games_luotchoi']);
Route::get('/games_ten/{id}', [GameController::class, 'get_games_ten']);
Route::get('/games_danhgia/{id}', [GameController::class, 'get_games_danhgia']);
Route::get('/games_page_sort/{sort}/{row}', [GameController::class,'getGamesPageSort']);
Route::get('/num_game', [GameController::class, 'getNumGame']);
Route::get('/get_sum_slc', [GameController::class, 'getSumLuotchoi']);
Route::get('/get_statistic', [GameController::class, 'getStatistic']);
Route::get('/games_sort_theloai/{id_theloai}/{sort}', [GameController::class, 'getGamesSortTheloai']);
Route::get('/games_search_sort/{keyword}/{sort}', [GameController::class, 'getGamesSearchSort']);
Route::get('/games_new', [GameController::class, 'getGamesNew']);
Route::get('/games_rate', [GameController::class, 'getGameRate']);
Route::get('/get_game_by_slug/{slug}', [GameController::class, 'getGameBySlug']);
Route::resource('game', GameController::class);
Route::get('/gettheloaipagesort/{sort}/{num}', [TheLoaiGameController::class, 'getTheloaiPageSort']);
Route::get('/num_theloai', [TheLoaiGameController::class, 'getNumTheloai']);
Route::get('/popular_theloai', [TheLoaiGameController::class, 'getPopularTheloai']);
Route::get('/theloai_by_slug/{slug}', [TheLoaiGameController::class, 'getTheloaiByBlug']);
Route::resource('theloai', TheLoaiGameController::class);
Route::post('/registry', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin_login', [AuthController::class, 'adminLogin']);
Route::get('/oauth/facebook', [OAuthController::class, 'redirect']);
Route::get('/facebook/callback', [OAuthController::class, 'handleCallback']);
Route::get('/report_page_sort/{sort}/{num}', [ReportController::class, 'getReportPageSort']);
Route::get('/reportnoprocess', [ReportController::class, 'getNoProcessReport']);
Route::get('/num_report', [ReportController::class, 'getNumReport']);
Route::resource('report', ReportController::class);
Route::resource('reply', ReplyController::class);
Route::post('/upload_image', [ArticleController::class, 'uploadImage']);
Route::get('/get_article_page_sort/{sort}/{num}', [ArticleController::class, 'getArticlePageSort']);
Route::get('/get_polular_article', [ArticleController::class, 'getPopularArticle']);
Route::get('/article_relate', [ArticleController::class, 'getArticleRelate']);
Route::get('/get_article_view', [ArticleController::class, 'getArticleView']);
Route::get('/get_article_new', [ArticleController::class, 'getArticleNew']);
Route::get('/article_by_slug/{slug}', [ArticleController::class, 'getArticleBySlug']);
Route::resource('article', ArticleController::class);
