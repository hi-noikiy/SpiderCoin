<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * 成功时路由跳转
     *
     * @param $route
     * @param $parameters
     * @param $message
     *
     * @return mixed
     */
    public function successRoutTo($route,$parameters = [], $message)
    {
        return redirect()->route($route , $parameters)->withSuccess($message);
    }

    /**
     * 成功时返回当前页
     *
     * @param $message
     *
     * @return mixed
     */
    public function successBackTo($message)
    {
        return redirect()->back()->withSuccess($message);
    }

    /**
     * 失败时路由跳转
     *
     * @param $route
     * @param $message
     *
     * @return $this
     */
    public function errorRouteTo($route, $message)
    {
        return redirect()->route($route)->withErrors($message);
    }

    /**
     * 失败时返回当前页
     *
     * @param $message
     *
     * @return $this
     */
    public function errorBackTo($message)
    {
        return redirect()->back()->withErrors($message)->withInput();
    }
}
