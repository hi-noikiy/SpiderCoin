<?php

namespace App\Libs;


use Illuminate\Auth\EloquentUserProvider as BaseEloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

class EloquentUserProvider extends BaseEloquentUserProvider
{
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'];
        $authPassword = $user->getAuthPassword();
        return $authPassword ==  md5($credentials['username'].$plain);

    }
}