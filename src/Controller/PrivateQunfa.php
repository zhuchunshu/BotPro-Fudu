<?php

namespace App\Plugins\fudu\src\Controller;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Plugins\fudu\src\Jobs\PrivateQunfa as JobsQunfa;

class PrivateQunfa extends Controller
{

    public function show(Content $content)
    {
        return $content
            ->title("复读机插件")
            ->description("复读机 - 私聊群发")
            ->body($this->grid());
    }

    protected function grid()
    {
        return new Grid(null, function (Grid $grid) {
            $grid->column('user_id', 'QQ号')->explode()->label();
            $grid->column('nickname', '昵称')->explode('\\')->label();
            $grid->column('remark', '备注名')->explode()->label();
            $grid->disableRowSelector();
            $grid->showBatchActions();
            // $grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();
            $grid->disablePagination();
            $grid->model()->setData($this->generate());
        });
    }
    public function generate()
    {
        $data = sendData([], "get_friend_list");
        return $data['data'];
    }

    public function create(Content $content)
    {
        return $content
            ->title("复读机")
            ->description("创建群发任务")
            ->body($this->form());
    }

    protected function form()
    {
        return Form::make(null, function (Form $form) {
            // $form->display('id');
            $data = sendData([], "get_friend_list");
            foreach ($data['data'] as $value) {
                $group[$value['user_id']]=$value['remark']."({$value['user_id']})";
            }
            $form->listbox('group','选择接收者')
                ->options($group)
                ->saving(function ($value) {
                    // 转化成json字符串保存到数据库
                    return json_encode($value);
            });
            $form->number('time','间隔时长(单位:秒)');
            $form->textarea('content', '群发内容')->required();
        });
    }
    public function store(Request $request,Form $form){
        $request->all();
        $content = $request->input('content',null);
        $time = $request->input('time',null);
        $group = array_diff($request->input('group'),[null]);
        if(!count($group)){
            return Json_Api(1,"选择了0个好友",'error');
        }
        if($content==null){
            return Json_Api(1,"群发内容为NULL",'error');
        }
        dispatch(new JobsQunfa($group,$content,$time));
        return Json_Api(1,"私聊群发完毕",'success');
    }
}
