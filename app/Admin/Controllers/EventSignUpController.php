<?php

namespace App\Admin\Controllers;

use App\Models\EventSignUp;
use App\Models\Event;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Table;

class EventSignUpController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        admin_js('/js/sortable.min.js');
        admin_css('/css/sortable-theme-bootstrap.css');
        admin_js('/js/sort-table.js');
        return $content
            ->header('活動報名')
            ->description('列表')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('admin.detail'))
            ->description('列表')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('admin.edit'))
            ->description('列表')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('admin.create'))
            ->description('列表')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EventSignUp);
        $grid->model()->orderBy('event_id', 'desc')->groupBy('event_id');

        $grid->column('event_id', '活動')->display(function($eid){
            $event = Event::find($eid);
            if($event)
            {
                $str = '';
                foreach($event->title as $k => $v)
                {
                    $title = $k == 'en' ? '英文' :'中文';
                    $str .= $title.' :<br>'. $v.'<br><br>';
                }
                return '<div style="margin-left: 30px;">'.$str.'</div>';
            }
        })->sortable();
        $grid->content('報名內容')->display(function($eid){
            return 'more';
        })->expand(function () {
            $event = Event::find($this->event_id);
            if($event)
            {
                $fields = $event->fields;

                $table = [];
                $table[] = '報名日期';
                foreach($fields as $field)
                {
                    $table[] = $field['name_zh-TW'];
                }
                $table[] = '付款方式';
                $table[] = '付款狀態';
                $table[] = '操作';
                $comments = EventSignUp::where('event_id', $this->event_id)->orderBy('created_at', 'desc')->get()->map(function ($comment) {
                    $data = json_decode($comment->content, true);
                    $sorted_data = [
                        'created_at' => date('Y-m-d', strtotime($comment->created_at)),
                        'name' => $data['name'],
                        'payment_method' => $data['payment'],
                        'paid' => $comment->paid == 1 ? '<span style="color:blue">已付款</span>':'<span style="color:red">未付款</span>',
                        'action' => '<a href="signups/'.$comment->id.'/edit">編輯</a>'
                    ];
                    return $sorted_data;
                });

                $table = new Table($table, $comments);
                $table->appendHtmlAttribute('data-sortable', '');
                return $table;
            }
        });

        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->disableCreateButton();

        $grid->disableActions();
        $grid = $this->sortGridByDate($grid);

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new EventSignUp);

        $form->display('email', 'email');
        $form->switch('paid', '付款狀態');
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
