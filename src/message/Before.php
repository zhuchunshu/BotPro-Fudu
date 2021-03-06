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
        $quanxian = false;
        if($this->data->sender->role=="admin"){
            $quanxian = true;
        }
        if($this->data->sender->role=="owner"){
            $quanxian = true;
        }
        if($quanxian){
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
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]无权操作"
            ], "send_group_msg");
        }
    }
    public function 踢(){
        $quanxian = false;
        if($this->data->sender->role=="admin"){
            $quanxian = true;
        }
        if($this->data->sender->role=="owner"){
            $quanxian = true;
        }
        if($quanxian){
            if ($this->orderCount >= 2) {
                $lahei = false;
                if(@$this->order[2]=="拉黑"){
                    $lahei = true;
                }
                $qq = cq_at_qq($this->data->message);
                if(is_numeric($qq)){
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'user_id' => $qq,
                        'reject_add_request' => $lahei
                    ], "set_group_kick");
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "[CQ:reply,id={$this->data->message_id}]已将 {$qq} 移出本群"
                    ], "send_group_msg");
                }else{
                    sendMsg([
                        'group_id' => $this->data->group_id,
                        'message' => "条件不满足，用法:
        踢 @被踢的人 (拉黑)"
                    ], "send_group_msg");
                }
            } else {
                sendMsg([
                    'group_id' => $this->data->group_id,
                    'message' => "条件不满足，用法:
    踢 @被踢的人 (拉黑)"
                ], "send_group_msg");
            }
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]无权操作"
            ], "send_group_msg");
        }
    }
    public function 安全吗(){
        if ($this->orderCount >= 2) {
            $data = sendData([
                'url' => $this->order[1]
            ],'check_url_safely');
            switch ($data['data']['level']) {
                case 1:
                    $text="安全";
                    break;
                case 2:
                    $text="不知道";
                    break;
                case 3:
                    $text="不安全";
                    break;
                default:
                    $text = "不知道".json_encode($data);
                    break;
            }
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "[CQ:reply,id={$this->data->message_id}]{$text}"
            ], "send_group_msg");
        }else{
            sendMsg([
                'group_id' => $this->data->group_id,
                'message' => "条件不满足，用法:
安全吗 链接"
            ], "send_group_msg");
        }
    }
}
