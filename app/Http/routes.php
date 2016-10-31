<?php
//Route::get('cors', function () {
//    return view('testCors');
//});

//Route::get('/', function () {
//    return redirect('backend/index/');
//});

/* 后台登录模块 */
Route::group(['namespace' => 'Backend\Auth'], function () {
    require_once __DIR__ . '/Routes/backendAuth.php';
});
/* 前台登录模块 */
Route::group(['namespace' => 'Frontend\Auth'], function () {
    require_once __DIR__ . '/Routes/frontendAuth.php';
});

Route::get('/', [
    'as'   => 'frontend.index.index',
    'uses' => 'Frontend\IndexController@index'
]);
/* 前端管理模块 */
Route::group([
    'middleware' => [
        'frontend.auth'
    ],
    'namespace' => 'Frontend'
], function () {

    require_once __DIR__ . '/Routes/frontend.php';
});

/* 后台管理模块 */
Route::group([
    'prefix'     => 'backend',
    'namespace'  => 'Backend',
    'middleware' => ['backend.auth'],
], function () {
    require_once __DIR__ . '/Routes/backend.php';
});


