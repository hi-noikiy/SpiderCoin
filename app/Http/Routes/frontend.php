<?php

/* 定投管理模块 */
Route::get('dingtou', [
    'as'         => 'backend.menu.search',
    'uses'       => 'MenuController@search',
]);
