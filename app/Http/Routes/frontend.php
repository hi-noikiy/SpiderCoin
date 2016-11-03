<?php
/* 定投管理模块 */
Route::resource('dingtou', 'Dingtou\AipController');
/* 市场key管理模块 */
Route::resource('market', 'Market\MarketController');
/* 搬砖管理模块 */
Route::resource('arb', 'Arb\ArbController');

/* 定投账单模块 */
Route::get('dingtou/{aip_id}/bill', [
    'as'         => 'dingtou.bill',
    'uses'       => 'Dingtou\AipBillController@index',
//    'middleware' => ['search'],
]);