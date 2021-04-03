<?php
namespace App\Plugins\fudu\src\message;

class One{
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
    public function 授权群(){
        foreach (authorizeGroup_get() as $value) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "$value"
            ], "send_group_msg");
        }
    }
    public function 插件信息(){
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => json_encode($this->PluginData,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        ], "send_group_msg");
    }
}