<?php

namespace App\Plugins\fudu\src\message;

use App\Plugins\fudu\src\Fudu;

/**
 * 后指令
 */
class Before
{

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

    public function __construct($data, $order, $orderCount, $PluginData)
    {
        $this->data = $data;
        $this->order = $order;
        $this->orderCount = $orderCount;
        $this->PluginData = $PluginData;
    }
    public function 链接分享()
    {
        if ($this->orderCount >= 3) {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:share,url={$this->order[1]},title={$this->order[2]}]"
            ], "send_group_msg");
        } else {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "条件不满足"
            ], "send_group_msg");
        }
    }
    public function 禁言()
    {
        if ($this->orderCount >= 4) {
            $duration = $this->order[2];
            if (is_numeric($duration)) {
                switch ($this->order[3]) {
                    case '分钟':
                        $duration = $duration*60;
                        break;
                    case '小时':
                        $duration = $duration*60*60;
                        break;
                    case '天':
                        $duration = $duration*60*60*24;
                        break;
                    default:
                        $duration = $duration*1;
                        break;
                }
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'user_id' => cq_at_qq($this->data->message),
                    'duration' => $duration
                ], "set_group_ban");
            } else {
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "禁言时长必须是数字"
                ], "send_group_msg");
            }
        } else {
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "条件不满足，用法:
禁言 @被禁言的人 时长 时间格式
禁言 @张三 60 秒(分钟、小时、天)"
            ], "send_group_msg");
        }
    }
}
