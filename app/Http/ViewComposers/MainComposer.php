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
            // 数据概览
            ['id'=>1,'name'=>'dingtou.index.index','description'=>'数据概览','route'=>'backend.index','parent_id'=>0,'hide'=>0,'icon'=>'fa fa-tachometer','sort'=>1],
            ['id'=>11,'name'=>'dingtou.index.index','description'=>'数据概览','route'=>'backend.index','parent_id'=>1,'hide'=>0,'icon'=>'fa fa-tachometer','sort'=>11],

            // 钱包管理
            ['id'=>2,'name'=>'dingtou.wallet.group','description'=>'钱包管理','route'=>'backend.wallet.group','parent_id'=>0,'hide'=>0,'icon'=>'fa fa-btc','sort'=>2],
            ['id'=>21,'name'=>'dingtou.wallet.list','description'=>'账单查询','route'=>'backend.wallet.info','parent_id'=>2,'hide'=>0,'icon'=>'fa fa-search','sort'=>21],

            // 用户管理
            ['id'=>3,'name'=>'dingtou.user.group','description'=>'用户管理','route'=>'backend.user.group','parent_id'=>0,'hide'=>0,'icon'=>'fa fa-user','sort'=>3],
            ['id'=>31,'name'=>'dingtou.user.list','description'=>'用户查询','route'=>'backend.user.bill','parent_id'=>3,'hide'=>0,'icon'=>'fa fa-search','sort'=>41],

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
