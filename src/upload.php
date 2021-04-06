<?php 
namespace App\Plugins\fudu\src;

class upload {
    
    /**
     * 接收到的数据
     *
     * @var object
     */
    public $data;

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
        $this->PluginData = $value;
        $this->boot();
    }

    /**
     * 插件启动方法
     *
     * @return void
     */
    public function boot(){
        $size = $this->getFilesize($this->data->file->size);
        sendMsg([
            'group_id' => $this->data->group_id,
            'message' => "[CQ:at,qq={$this->data->user_id}]上传了一个文件,\n\n文件名:{$this->data->file->name}\n\n文件大小:{$size}\n\n文件直链:{$this->data->file->url}"
        ], "send_group_msg");
    }

    public function getFilesize($num){
       $p = 0;
       $format='bytes';
       if($num>0 && $num<1024){
         $p = 0;
         return number_format($num).' '.$format;
       }
       if($num>=1024 && $num<pow(1024, 2)){
         $p = 1;
         $format = 'KB';
      }
      if ($num>=pow(1024, 2) && $num<pow(1024, 3)) {
        $p = 2;
        $format = 'MB';
      }
      if ($num>=pow(1024, 3) && $num<pow(1024, 4)) {
        $p = 3;
        $format = 'GB';
      }
      if ($num>=pow(1024, 4) && $num<pow(1024, 5)) {
        $p = 3;
        $format = 'TB';
      }
      $num /= pow(1024, $p);
      return number_format($num, 3).' '.$format;
    }

}