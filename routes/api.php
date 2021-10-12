<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\FranchiseManagementController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PrivilegesUserController;
use App\Http\Controllers\VideoContentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CountryMstController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\BioContentController;
use App\Http\Controllers\PromosController;

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

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login'])->name('login');
Route::post('forgotpass',[passportAuthController::class,'forgotpass']);
Route::post('resetpass',[passportAuthController::class,'resetpass']);

Route::resource('images',ImageController::class);
Route::middleware('auth:api')->group(function () {
   
    Route::resource('users',UserManagementController::class);
    Route::post('usersearch',[UserManagementController::class,'search_users']);
    Route::post('users/bulk_delete',[UserManagementController::class,'bulkDeleteUser']);

    Route::resource('privilegesUser',PrivilegesUserController::class);
    Route::resource('images',ImageController::class);
    Route::post('images/search',[ImageController::class,'searchByTitle']);
    Route::post('images/searchItem',[ImageController::class,'searchItem']);
    Route::post('images/search_language',[ImageController::class,'searchByLanguage']);
    Route::post('images/search_status',[ImageController::class,'searchByStatus']);
    Route::post('images/bulk_delete',[ImageController::class,'bulkDeleteImages']);
    Route::post('images/update',[ImageController::class,'update']);
    Route::post('images/list',[ImageController::class,'listImage']);

    Route::resource('documents',DocumentsController::class);
    Route::post('documents/update',[DocumentsController::class,'update']);
    Route::post('documents/search',[DocumentsController::class,'searchByTitle']);
    Route::post('documents/bulk_delete',[DocumentsController::class,'bulkDeleteDocuments']);
    Route::post('documents/search_status',[DocumentsController::class,'searchByStatus']);
    Route::post('documents/search_language',[DocumentsController::class,'searchByLanguage']);
    Route::post('documents/searchItem',[DocumentsController::class,'searchItem']);
    Route::post('documents/list',[DocumentsController::class,'listDocuments']);
    // Promos
    Route::resource('promos',PromosController::class);
    Route::post('promos/update',[PromosController::class,'update']);
    Route::post('promos/search',[PromosController::class,'searchByTitle']);
    Route::post('promos/search_language',[PromosController::class,'filterByLanguage']);
    Route::post('promos/search_status',[PromosController::class,'searchByStatus']);
    Route::post('promos/bulk_delete',[PromosController::class,'bulkDeletePromos']);
    Route::post('promos/searchItem',[PromosController::class,'searchItem']);
    Route::post('promos/list',[PromosController::class,'listPromos']);


    Route::resource('posts', PostController::class);
    Route::post('players/multi_players',[PlayerController::class,'insertMultiPlayers']);
    Route::post('players/search',[PlayerController::class,'searchPlayers']);
    Route::post('players/bulk_delete',[PlayerController::class,'bulkDeletePlayers']);
    Route::post('players/{id}',[PlayerController::class,'update']);
    Route::resource('players', PlayerController::class);
    Route::post('articles/list',[ArticleController::class,'listArticles']);
    Route::post('articles/search',[ArticleController::class,'searchByTitle']);
    Route::post('articles/search_language',[ArticleController::class,'searchByLanguage']);
    Route::post('articles/search_status',[ArticleController::class,'searchByStatus']);
    Route::post('articles/bulk_delete',[ArticleController::class,'bulkDeleteArticles']);
    Route::post('articles/{id}',[ArticleController::class,'update']);
    Route::resource('articles', ArticleController::class);

    Route::post('addFranchise',[FranchiseManagementController::class,'addFranchise']);
    Route::get('listFranchise',[FranchiseManagementController::class,'listFranchise']);
    Route::post('updateFranchise/{id}',[FranchiseManagementController::class,'updateFranchise']);
    Route::get('{name}/searchFranchise', [FranchiseManagementController::class,'searchFranchise']);
    Route::delete('/bulkDeleteFranchises', [FranchiseManagementController::class,'bulkDeleteFranchises']);
    Route::get('{id}/getFranchises', [FranchiseManagementController::class,'getFranchises']);
    // Route::post('franchises/list',[FranchiseManagementController::class,'listFranchiseByTag']);

    Route::post('addVideo',[VideoContentController::class,'addVideo']);
    Route::get('listVideo',[VideoContentController::class,'listVideo']);
    Route::delete('bulkDeleteVideo', [VideoContentController::class,'bulkDeleteVideo']);
    Route::get('{title}/searchByTitle',[VideoContentController::class,'searchByTitle']);
    Route::get('{language}/filterByLanguage',[VideoContentController::class,'filterByLanguage']);
    Route::get('{status}/filterByStatus',[VideoContentController::class,'filterByStatus']);
    Route::post('search',[CommonController::class,'searchData']);
    Route::delete('{id}/deleteVideo',[VideoContentController::class,'deleteVideo']);
    Route::get('listCountry',[CountryMstController::class,'listCountry']);
    Route::get('{id}/viewVideoById',[VideoContentController::class,'viewVideoById']);
    Route::post('{id}/updateVideo',[VideoContentController::class,'updateVideo']);
    Route::post('videos/list',[VideoContentController::class,'listVideoByTag']);

    Route::post('addBio',[BioContentController::class,'addBio']);
    Route::get('listBio',[BioContentController::class,'listBio']);
    Route::delete('bulkDeleteBio', [BioContentController::class,'bulkDeleteBio']);
    Route::get('{title}/searchByTitleBio',[BioContentController::class,'searchByTitleBio']);
    Route::get('{language}/filterByLanguageBio',[BioContentController::class,'filterByLanguageBio']);
    Route::get('{status}/filterByStatusBio',[BioContentController::class,'filterByStatusBio']);
    Route::delete('{id}/deleteBio',[BioContentController::class,'deleteBio']);
    Route::get('{id}/viewBioById',[BioContentController::class,'viewBioById']);
    Route::post('{id}/updateBio',[BioContentController::class,'updateBio']);
    Route::post('bios/list',[BioContentController::class,'listBioByTag']);

    Route::post('playlists/list',[PlaylistController::class,'listPlaylists']);
    Route::post('playlists/search',[PlaylistController::class,'searchByTitle']);
    Route::post('playlists/search_language',[PlaylistController::class,'searchByLanguage']);
    Route::post('playlists/search_status',[PlaylistController::class,'searchByStatus']);
    Route::post('playlists/bulk_delete',[PlaylistController::class,'bulkDeletePlaylists']);
    Route::post('playlists/{id}',[PlaylistController::class,'update']);
    Route::resource('playlists', PlaylistController::class);
    Route::post('audios/list',[AudioController::class,'listAudios']);
    Route::post('audios/search',[AudioController::class,'searchByTitle']);
    Route::post('audios/search_language',[AudioController::class,'searchByLanguage']);
    Route::post('audios/search_status',[AudioController::class,'searchByStatus']);
    Route::post('audios/bulk_delete',[AudioController::class,'bulkDeleteAudios']);
    Route::post('audios/{id}',[AudioController::class,'update']);
    Route::resource('audios', AudioController::class);

});