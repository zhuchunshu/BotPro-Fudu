<?php
namespace App\Plugins\fudu\src\message;

use App\Plugins\fudu\src\Fudu;

/**
 * 后指令
 */
class Before{

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

    public function __construct($data,$order,$orderCount,$PluginData)
    {
        $this->data = $data;
        $this->order = $order;
        $this->orderCount = $orderCount;
        $this->PluginData = $PluginData;
    }
    public function 链接分享(){
        if($this->orderCount>=3){
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:share,url={$this->order[1]},title={$this->order[2]}]"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "条件不满足"
            ], "send_group_msg");
        }
    }
}