<?php
// 登录认证
Route::get('backend/auth/login', 'AuthController@getLogin');
Route::post('backend/auth/login', 'AuthController@postLogin');
Route::get('backend/auth/logout', 'AuthController@getLogout');

// 用户注册
Route::get('backend/auth/register', 'AuthController@getRegister');
Route::post('backend/auth/register', 'AuthController@postRegister');