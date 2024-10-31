<?php

namespace App\Admin\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class NewsController extends Controller
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
            ->header('最新消息')
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
        $grid = new Grid(new News);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });

        $grid->model()->orderBy('id', 'desc');
        $grid->column('title', '標題')->sortable();
        $grid->description('內容')->width(800);
        $grid->image('圖片')->image();

        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

        $grid->filter(function($filter){

            $filter->disableIdFilter();

            $filter->like('title', '標題');
            $filter->like('description', '內容');

        });
        $grid = $this->sortGridByDate($grid);

        $grid->setDialogFormDimensions('90%', '95%');
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new News);

        $form->text('title', '標題')->required();
        $form->editor('description', '內容')->required();
        $form->textarea('Head', 'Head內容');
        $form->image('image', '圖片')->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920')->attribute(['class' => 'form-control'])->uniqueName()->autoUpload()->required();
        $form->html('<small>最適尺寸 1000px x 1000px (1:1)</small>');
        $form->html('<small>上傳檔案大小上限 2MB, 最大尺寸 1920px x 1920px</small>');
        //$form->select('category_id', '類別')->options(NewsCategory::all()->pluck('title','id'));
        $form->hidden('category_id')->value(1);
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        return $form;
    }
}
