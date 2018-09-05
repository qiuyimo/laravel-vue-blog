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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

/**
 * 获取 github 的 code.
 * 文档. https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/
 */
Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => config('services.github.client_id'),
        'redirect_uri' => 'http://blog.qiuyuhome.com/github/callback',
        'scope' => '*',
        'state' => config('services.github.state'),
        'allow_signup' => true,
        'response_type' => 'code',
    ]);

    return redirect('https://github.com/login/oauth/authorize?'.$query);
});

/**
 * 获取 access_token.
 */
Route::get('/github/callback', function (Request $request) {
    // 根据 code, 获取 access_token.
    $http = new GuzzleHttp\Client;
    $response = $http->post('https://github.com/login/oauth/access_token', [
        'form_params' => [
            'client_id' => config('services.github.client_id'),
            'client_secret' => config('services.github.client_secret'),
            'code' => $request->code,
            'redirect_uri' => 'http://blog.qiuyuhome.com/github/callback',
            'state' => config('services.github.state'),
            'grant_type' => 'authorization_code',
        ],
    ]);
    parse_str((string)$response->getBody(), $resArr);
    $accessToken = $resArr['access_token'];

    // 根据获取到的 access, 获取用户信息.
    $response = $http->get('https://api.github.com/user?access_token=' . $accessToken);
    $userInfo = (string)$response->getBody();
    return $userInfo;
});
