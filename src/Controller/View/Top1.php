<?php

namespace App\Plugins\fudu\src\Controller\View;

use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Dcat\Admin\Widgets\Metrics\Bar;

class Top1
{
    /**
     * 初始化卡片内容
     */
    public function index()
    {
        $plugin_data = get_plugin_data("fudu");
        if ($plugin_data) {
            $card = Card::make('复读机', $this->content($plugin_data));
            $card->tool('<a href="'.$plugin_data->url.'" id="recqhttp" class="btn btn-sm btn-dark shadow-none">插件官网</a>');
        } else {
            admin_script(
                <<<JS
                Dcat.error('插件信息读取失败');
                Dcat.swal.error('插件信息读取失败', '可能存在以下问题:<br>1.安装了盗版插件<br>2.插件目录命名有误<br>3.插件data.json文件内容格式不正确');
JS
            );
            $card = Card::make('复读机', "插件信息读取失败");
            $card->tool('<button class="btn btn-sm btn-dark shadow-none">是不是安装了盗版插件?</button>');
            //$card->tool('<button onclick="alert('.'a'.')" class="btn btn-sm btn-dark shadow-none">你是不是安装了盗版插件?</button>');
        }
        return $card;
    }

    public function content($plugin_data)
    {
        return <<<HTML
        <h3>作者: <a href="{$plugin_data->url}">{$plugin_data->author}</a></h3>
        <h3>插件版本: 「{$plugin_data->version}」</h3>
        <h4>插件描述: 「{$plugin_data->description}」</h4>
HTML;
    }
}
