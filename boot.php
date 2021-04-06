<?php

namespace App\Plugins\fudu;

use App\Models\Option;
use App\Plugins\fudu\src\Controller\Fuduji;
use Dcat\Admin\Admin;
use Dcat\Admin\Layout\Menu;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Plugins\fudu\src\Controller\GroupQunfa;
use App\Plugins\fudu\src\Controller\PrivateQunfa;
use App\Plugins\fudu\src\Controller\IndexController;
use App\Plugins\fudu\src\Controller\Sendannouncement;

class boot{
    
    public function handle(){
        // $this->route();
        $this->menu();
        $this->route();
        $this->SqlReg();
    }

    // 定义插件路由
    public function route(){
        Route::group([
            'prefix'     => config('admin.route.prefix'),
            'middleware' => config('admin.route.middleware'),
        ], function () {
            // Route::get('fuduji', [IndexController::class,'show']);
            Route::prefix('fuduji')->group(function () {
                // 复读机
                Route::get('/', [Fuduji::class,'index']);
                // 群组群发视图
                Route::get('/qunfa/group', [GroupQunfa::class,'show']);
                // 群组群发创建
                Route::get('/qunfa/group/create', [GroupQunfa::class,'create']);
                Route::post('/qunfa/group', [GroupQunfa::class,'store']);

                // 私聊群发视图
                Route::get('/qunfa/private', [PrivateQunfa::class,'show']);
                // 私聊群发创建
                Route::get('/qunfa/private/create', [PrivateQunfa::class,'create']);
                Route::post('/qunfa/private', [PrivateQunfa::class,'store']);
                 // 批量发群公告
                Route::get('/qunfa/announcement/create', [Sendannouncement::class,'create']);
                Route::post('/qunfa/announcement', [Sendannouncement::class,'store']);
            });
        });        
    }

    public function menu(){
        // 注册菜单
        Admin::menu(function (Menu $menu) {
            $menu->add([
                [
                    'id'            => 100, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '复读机',
                    'icon'          => 'feather icon-printer',
                    'uri'           => 'fuduji',
                    'parent_id'     => 0, 
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],   
                [
                    'id'            => 101, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '复读机',
                    'icon'          => '',
                    'uri'           => 'fuduji',
                    'parent_id'     => 100, 
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
                [
                    'id'            => 102, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '群组群发',
                    'icon'          => '',
                    'uri'           => 'fuduji/qunfa/group',
                    'parent_id'     => 100, 
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
                [
                    'id'            => 103, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '私聊群发',
                    'icon'          => '',
                    'uri'           => 'fuduji/qunfa/private',
                    'parent_id'     => 100, 
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],   
                [
                    'id'            => 104, // 此id只要保证当前的数组中是唯一的即可
                    'title'         => '批量发群公告',
                    'icon'          => '',
                    'uri'           => 'fuduji/qunfa/announcement/create',
                    'parent_id'     => 100, 
                    'permission_id' => 'administrator', // 与权限绑定
                    'roles'         => 'administrator', // 与角色绑定
                ],
            ]);
        });
    }
    public function SqlReg(){
        if(!Option::where('name','BOT_QQ')->count()){
            $get_login_info = sendData([], "get_login_info");
            Option::insert([
                'name' => 'BOT_QQ',
                'value' => $get_login_info['data']['user_id'],
                'created_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}