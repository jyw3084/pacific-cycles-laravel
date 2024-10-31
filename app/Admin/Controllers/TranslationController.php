<?php

namespace App\Admin\Controllers;

use App\Models\Translation;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Spatie\TranslationLoader\LanguageLine;

class TranslationController extends Controller
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
            ->header(trans('admin.index'))
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
        $grid = new Grid(new LanguageLine);
        $grid->quickSearch('key','group');
        $grid->disableCreateButton();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableDelete();
        });
        $grid->group(trans('translation.group'))->sortable();
        $grid->key(trans('translation.key'))->sortable();
        $grid->text(trans('translation.text'))->display(function ($text) {
            $text = "<span class='label label-primary'>".trans('translation.en')." :</span> {$text['en']}<br>
            <span class='label label-primary'>".trans('translation.zh-TW')." :</span> {$text['zh-TW']}";
            return $text;
        });
        

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new LanguageLine);

       
        $form->text('group');
        $form->text('key');
        $form->keyValue('text')->value(array('en'=>'','zh-TW'=>''));
        // dd($form);

        return $form;
    }
}
