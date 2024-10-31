<?php

namespace App\Admin\Controllers;

use App\Models\Faq;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class FaqController extends Controller
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
        return $content
            ->header('問與答')
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
        $grid = new Grid(new Faq);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('type', '車型', [1 => 'BIRDY', 2 => 'CARRY ME', 3 => 'IF', 4 => 'REACH', 5 => 'SUPPORTIVE']);
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        $grid->disableRowSelector();
        $grid->disableFilter();
        $grid->type('車型')->using([1 => 'BIRDY', 2 => 'CARRY ME', 3 => 'IF', 4 => 'REACH', 5 => 'SUPPORTIVE']);
        $grid->question('問題');
        $grid->answer('答覆')->display(function($answer){
            return nl2br($answer);
        })->width(800);
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);
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
        $form = new Form(new Faq);

        $form->radio('type', '車型')->options([1 => 'BIRDY', 2 => 'CARRY ME', 3 => 'IF', 4 => 'REACH', 5 => 'SUPPORTIVE'])->required();
        $form->text('question', '問題');
        $form->textarea('answer', '答覆');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();
        return $form;
    }
}
