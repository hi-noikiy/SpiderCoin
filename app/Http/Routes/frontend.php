<?php

/* 定投管理模块 */
Route::group(['namespace'  => 'Dingtou',
//    'middleware' => ['backend.auth'],
], function () {
    Route::get('dingtou', [
        'as'         => 'frontend.dingtou.index',
        'uses'       => 'IndexController@index',
    ]);
});