<?php
namespace App\Plugins\fudu\src\Controller;

use App\Http\Controllers\Controller;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;

class Fuduji extends Controller {

    public function index(Content $content){
        return $content
        ->header('复读机')
        ->description('复读机插件信息')
        ->body(function (Row $row) {

            $row->column(12, function (Column $column) {

                $column->row((new View\Top1())->index());
            });
        });
    }

}