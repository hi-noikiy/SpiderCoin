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
        $route = Route::currentRouteName();
//        $menus = MenuRepository::getAllDisplayMenus();

        $menus = [
            // 主管理模块
            ['id'=>1,'name'=>'dingtou.menu.group','description'=>'操作管理','route'=>'backend.index','parent_id'=>0,'hide'=>0,'icon'=>12,'sort'=>12],
//            ['id'=>5,'name'=>'dingtou.menu.group','description'=>'操作管理','route'=>'backend.menu','parent_id'=>3,'hide'=>0,'icon'=>12,'sort'=>12],
            ['id'=>2,'name'=>'dingtou.menu.group','description'=>'操作管理','route'=>'backend.index','parent_id'=>1,'hide'=>0,'icon'=>12,'sort'=>12],
//            ['id'=>3,'name'=>'dingtou.menu.group','description'=>3,'route'=> 'backend.menu','parent_id'=>1,'hide'=> 0,'icon'=> 12,'sort'=> 12,],

            // 二级模块

        ];
        $title = $this->getPageDescriptionArrayByMenus($menus);
        $view->with( compact('menus', 'route', 'title') );
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
