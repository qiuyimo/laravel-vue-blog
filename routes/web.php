<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/github/callback', function () {
//     echo 'ok';
// });

/**
 * passport client test
 */
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '9aba3c14c3aa9d1bf4df',
        'redirect_uri' => 'http://blog.qiuyuhome.com/github/callback',
        'response_type' => 'code',
        'scope' => '*',
    ]);

    return redirect('https://github.com/login/oauth/authorize?'.$query);
});

/**
 * passport callback
 */
Route::get('/github/callback', function (Request $request) {
    $http = new GuzzleHttp\Client;

    $response = $http->post('https://github.com/login/oauth/access_token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '9aba3c14c3aa9d1bf4df',
            'client_secret' => 'fc81cc7e20e87cb1b7783a80bc094fb22275732c',
            'redirect_uri' => 'http://blog.qiuyuhome.com/github/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});
