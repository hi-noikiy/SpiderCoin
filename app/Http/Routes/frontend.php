<?php
/* 定投管理模块 */
Route::resource('dingtou', 'Dingtou\AipController');
/* 定投管理模块 */
Route::resource('market', 'Market\MarketController');
/* 定投账单模块 */
Route::get('dingtou/{aip_id}/bill', [
    'as'         => 'dingtou.bill',
    'uses'       => 'Dingtou\AipBillController@index',
//    'middleware' => ['search'],
]);