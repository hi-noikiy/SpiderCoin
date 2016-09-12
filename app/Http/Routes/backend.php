<?php

/* 后台首页 */
Route::get('/', [
    'as'   => 'backend.index',
    'uses' => 'IndexController@index',
]);

/* 菜单管理模块 */
Route::get('menu/search', [
    'as'         => 'backend.menu.search',
    'uses'       => 'MenuController@search',
    'middleware' => ['search'],
]);
/* 操作管理模块 */
Route::resource('action', 'ActionController');