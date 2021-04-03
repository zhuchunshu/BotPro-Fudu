<?php
namespace App\Plugins\fudu\src;

use App\Plugins\fudu\src\message\Before;
use App\Plugins\fudu\src\message\One;

class Fudu {

    /**
     * 接收到的数据
     *
     * @var object
     */
    public $data;

    /**
     * 指令
     *
     * @var array
     */
    public $order;

    /**
     * 指令数量
     *
     * @var integer
     */
    public $orderCount;

    /**
     * 插件信息
     *
     * @var array
     */
    public $PluginData;

    /**
     * 插件注册
     *
     * @param object 接收到的数据 $data
     * @param array 插件信息 $value
     * @return void
     */
    public function register($data,$value){
        $this->data = $data;
        $this->order = $order = GetZhiling($data," ");
        $this->orderCount = count($order);
        $this->PluginData = $value;
        $this->boot();
    }

    /**
     * 插件启动
     *
     * @return void
     */
    public function boot(){
        // 两个以上指令
        if($this->orderCount>=2){
            $arr = [
                Before::class
            ];
            $this->Run($arr);
        }
        // 单指令
        if($this->orderCount==1){
            $arr = [
                One::class
            ];
            $this->Run($arr);
        }

    }

    public function Run(array $arr){
        foreach ($arr as $value) {
            if(method_exists(new $value($this->data,$this->order,$this->orderCount,$this->PluginData),$this->order[0])){
                $method = $this->order[0];
                (new $value($this->data,$this->order,$this->orderCount,$this->PluginData))->$method();
            }
        }
    }
}