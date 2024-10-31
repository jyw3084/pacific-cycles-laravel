<?php

namespace App\Admin\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class EventController extends Controller
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
            ->header('活動')
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
        $grid = new Grid(new Event);
        $grid->model()->orderBy('id', 'desc');
        $grid->column('title', '標題')->display(function($title){
            $str = '';
            if($title)
            {
                foreach($title as $k => $v)
                {
                    $key = $k == 'en' ? '英文' :'中文';
                    $str .= $key.' :<br>'. $v.'<br><br>';
                }
            }
            return $str;
        });
        $grid->column('description', '內容')->display(function($description){
            $str = '';
            if($description)
            {
                foreach($description as $k => $v)
                {
                    $key = $k == 'en' ? '英文' :'中文';
                    $str .= $key.' :<br>'. $v.'<br><br>';
                }
            }
            return $str;
        })->width(800);
        $grid->image('圖片')->image();

        $grid->start_date('開始日期');
        $grid->end_date('結束日期');
        $grid->column('canceled', '狀態')->switch();

        $grid->price('報名費');
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
        $form = new Form(new Event);

        $form->select('locale', 'Locale')->options(['all' => 'all', 'en' => '英文', 'zh-TW' => '中文'])->attribute('onchange', 'toggleEventLocale($(this).val())')->attribute('id', 'localeId')->default('all')->attribute('required', 'required');

        $form->embeds('title', '標題', function ($form) {

            $form->text('en', '英文')->setFormGroupClass('localized')->setFormGroupClass('en')->addElementClass('en')->attribute('required', 'required');
            $form->text('zh-TW', '中文')->setFormGroupClass('localized')->setFormGroupClass('zh-TW')->addElementClass('zh-TW')->attribute('required', 'required');
        })->attribute('required', 'required');

        $form->embeds('description', '內容', function ($form) {

            $form->textarea('en', '英文')->setFormGroupClass('localized')->setFormGroupClass('en')->addElementClass('en')->attribute('required', 'required');
            $form->textarea('zh-TW', '中文')->setFormGroupClass('localized')->setFormGroupClass('zh-TW')->addElementClass('zh-TW')->attribute('required', 'required');
        })->attribute('required', 'required');
        $form->embeds('head', 'Head內容', function ($form) {

            $form->textarea('en', '英文')->setFormGroupClass('localized')->setFormGroupClass('en')->addElementClass('en')->addElementClass('optional');
            $form->textarea('zh-TW', '中文')->setFormGroupClass('localized')->setFormGroupClass('zh-TW')->addElementClass('zh-TW')->addElementClass('optional');
        });
        $form->image('image', '圖片')->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920')->uniqueName()->autoUpload()->attribute('required', 'required');
        $form->html('<small>最適尺寸 1000px x 1000px (1:1)</small>');
        $form->html('<small>上傳檔案大小上限 2MB, 最大尺寸 1920px x 1920px</small>');
        //$form->select('category_id', '類別')->options(EventCategory::all()->pluck('title','id'));
        $form->date('start_date', '開始日期')->attribute('required', 'required');
        $form->date('end_date', '結束日期')->attribute('required', 'required');
        $form->array('fields', '報名表欄位', function ($table) {
            $table->text('key', '鍵值(只能輸入英文)')->attribute('required', 'required');
            $table->text('name_zh-TW', '欄位名稱(中文)')->setFormGroupClass('localized')->setFormGroupClass('zh-TW')->addElementClass('zh-TW')->addElementClass('optional');
            $table->text('name_en', '欄位名稱(英文)')->setFormGroupClass('localized')->setFormGroupClass('en')->addElementClass('en')->addElementClass('optional');
            $table->select('category', '類型')->options([1 => 'input', 2 => 'textarea', 3 => 'select'])->attribute('required', 'required');
            $table->select('type', '樣式')->options([1 => 'text', 2 => 'email', 3 => 'number', 4 => 'radio', 5 => 'checkbox', 6 => 'date']);
            $table->tags('options', '項目');
            $table->select('col', '寬度')->options([6 => '6', 12 => '12'])->attribute('required', 'required');
            $table->switch('required', '必填');
            $table->html("<script>
            console.log('ok');
            var locale = $('#localeId').val();
                toggleEventLocale(locale);
            </script>");
        });
        $form->number('price', '報名費')->attribute('required', 'required');
        $form->switch('canceled', '狀態')->default(0);

        $form->html("
            <script>
            function toggleEventLocale(locale){
                $('.localized').css('display', 'flex');
                $('.localized input').attr('required', 'required');
                $('.localized textarea').attr('required', 'required');
                $('.optional').removeAttr('required');
                if(locale!='all'){
                    $('input').not('.'+locale).removeAttr('required');
                    $('textarea').not('.'+locale).removeAttr('required');
                    $('.localized').not('.'+locale).css('display', 'none');
                }
            }

            toggleEventLocale($('#localeId').val());
            </script>
        ");

        return $form;
    }
}
