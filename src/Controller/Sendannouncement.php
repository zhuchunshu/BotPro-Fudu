<?php

namespace App\Plugins\fudu\src\Controller;

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use App\Plugins\fudu\src\Jobs\announcementJob;
use App\Plugins\fudu\src\Jobs\GroupQunfa as JobsGroupQunfa;
use Illuminate\Http\Request;

class Sendannouncement extends Controller
{

    public function show(Content $content)
    {
        return $content
            ->title("复读机插件")
            ->description("复读机 - 群组群发")
            ->body($this->grid());
    }

    protected function grid()
    {
        return new Grid(null, function (Grid $grid) {
            $grid->setName('已加入的群');
            $grid->column('group_id', '群号')->explode()->label();
            $grid->column('group_name', '群名')->explode('\\')->label();
            $grid->column('member_count', '群人数');
            $grid->column('max_member_count', '群最大人数');
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
        $data = sendData([], "get_group_list");
        return $data['data'];
    }

    public function create(Content $content)
    {
        return $content
            ->title("复读机")
            ->description("批量发群公告")
            ->body($this->form());
    }

    protected function form()
    {
        return Form::make(null, function (Form $form) {
            // $form->display('id');
            $data = sendData([], "get_group_list");
            foreach ($data['data'] as $value) {
                //$group[$value['group_id']]=$value['group_name'];
                $gudata = sendData([
                    'group_id' => $value['group_id'],
                    'user_id' => get_options('BOT_QQ')
                ], "get_group_member_info");
                if($gudata['data']['role']!="member"){
                    $group[$value['group_id']]=$value['group_name'];
                }
            }
            $form->listbox('group','选择群')
                ->options($group)
                ->saving(function ($value) {
                    // 转化成json字符串保存到数据库
                    return json_encode($value);
            });
            $form->number('time','间隔时长(单位:秒)')->max(10)->min(0);
            $form->textarea('content', '公告内容')->required();
        });
    }
    public function store(Request $request,Form $form){
        $request->all();
        $content = $request->input('content',null);
        $time = $request->input('time',null);
        $group = array_diff($request->input('group'),[null]);
        if(!count($group)){
            return Json_Api(1,"选择了0个群",'error');
        }
        if($content==null){
            return Json_Api(1,"内容为NULL",'error');
        }
        dispatch(new announcementJob($group,$content,$time));
        return Json_Api(1,"搞定啦!",'success');
    }
}
