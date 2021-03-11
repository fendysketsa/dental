<?php

use Illuminate\Http\Request;
// use Illuminate\Routing\Route;

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

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('user/profile', 'Api\ProfileController@index');
    Route::put('user/profile', 'Api\ProfileController@update');
    Route::put('user/review', 'Api\ProfileController@review');
    Route::post('user/profile/upload', 'Api\ProfileController@store_upload');
    Route::post('reservation', 'Api\ReservationController@store');
    Route::post('reservation/upload', 'Api\ReservationController@store_upload');
    Route::get('reservation', 'Api\ReservationController@index');
    Route::get('user/schedule', 'Api\ReservationController@_jadwal');

    Route::post('reservation/paket', 'Api\ReservationPaketController@store');
    Route::post('reservation/paket/upload', 'Api\ReservationPaketController@store_upload');
    Route::get('reservation/paket', 'Api\ReservationPaketController@index');

    Route::post('buy/produk', 'Api\KeranjangBelanjaController@store');
    Route::get('buy/produk', 'Api\KeranjangBelanjaController@index');
    Route::get('histori/transaksi/all', 'Api\HistoriTransaksiAllController@index');
    Route::put('update-fcm', 'Api\ProfileController@updateFCM');
    Route::get('test-fcm', 'Web\Monitoring\OrderController@activations');
});

Route::apiResource('member', 'Api\Member\MemberController');
Route::apiResource('terapist', 'Api\TerapistController');
Route::apiResource('cabang', 'Api\Cabang\CabangController');
Route::apiResource('bank', 'Api\BankController');
Route::apiResource('slide', 'Api\SlideController');
Route::apiResource('medina', 'Api\MedinaController');
Route::apiResource('medina/{id}', 'Api\MedinaController');
Route::apiResource('room', 'Api\RoomController');
Route::apiResource('promo', 'Api\PromoController');
Route::apiResource('news', 'Api\NewsController');
Route::apiResource('cat/layanan', 'Api\CategoryService');
Route::apiResource('cat/produk', 'Api\CategoryProduct');
Route::put('list/cat/produk/{id}', 'Api\ProdukController@index');
Route::apiResource('paket', 'Api\PaketController');
Route::put('produk/show/{id}', 'Api\ProdukController@show');
Route::put('promo/show/{id}', 'Api\PromoController@show');
Route::put('list/cat/layanan/{id}', 'Api\LayananController@index');
Route::put('layanan/show/{id}', 'Api\LayananController@show');
Route::post('actived/member', 'Api\RegisterController@index');
Route::apiResource('reg/member', 'Api\RegisterController');

Route::get('auth/{provider}', 'Auth\AuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\AuthController@handleProviderCallback');
