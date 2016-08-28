<?php

namespace App\Http\ViewComposers;

use App\Facades\MenuRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

class MainComposer
{
    /**
     * 将数据绑定到视图
     *
     * @param  View $view
     *
     * @return mixed
     */
    public function compose(View $view)
    {
//        $route = Route::currentRouteName();
//        $menus = MenuRepository::getAllDisplayMenus();
        $route = [
            1,2,3
        ];
        $menus = [
            [
                'id'            => 1,
                'name'          => '数据管理',
                'description'   => '操作管理',
                'route'         => 1,
                'parent_id'     => 0,
                'hide'          => 0,
                'icon'          => 12,
                'type'          => 12,
            ], [
                'id'            => 2,
                'name'          => '用户管理',
                'description'   => '操作管理',
                'route'         => 2,
                'parent_id'     => 0,
                'hide'          => 0,
                'icon'          => 12,
                'type'          => 12,
            ], [
                'id'            => 3,
                'name'          => '系统管理',
                'description'   => 3,
                'route'         => 3,
                'parent_id'     => 1,
                'hide'          => 0,
                'icon'          => 12,
                'type'          => 12,
            ],
        ];
        $title = $this->getPageDescriptionArrayByMenus($menus);
        $view->with(compact('menus', 'route', 'title'));
    }

    private function getPageDescriptionArrayByMenus($menus)
    {
        $arr = [];
        foreach ($menus as $menu) {
            $arr[$menu['route']]['name'] = $menu['name'];
            $arr[$menu['route']]['description'] = $menu['description'];
        }

        return $arr;
    }
}
