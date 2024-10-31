<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use DB;

class AccessoriesController extends Controller
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
        $grid = new Grid(new Products);
        $grid->model()->where('type', 2);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });

        $grid->disableColumnSelector();
        $grid->product_name('配件名稱');
        $grid->product_code('URL');
        $grid->quantity('數量');
        $grid->price('價格');
        $grid->color('顏色')->label('primary');
        $grid->images('圖片')->image();

        $grid->priority('priority')->sortable();

        $grid->column('category_id', '類別')->display(function($category_id){
            if($category_id)
                return "<span class='label label-primary'>".$this->category->name."</span>";
            else
                return '';
        });
        $grid->column('model','車型')->display( function($model_id){
            if($model_id)
                return "<span class='label label-primary'>".$this->bike_model->bike_model."</span>";
            else
                return '';
        });
        $grid->content('內容')->display(function(){
            return '顯示';
        })->modal('內容', function ($model) {

            return $this->content;
        });

        $grid = $this->sortGridByDate($grid);

        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

        $grid->disableFilter();
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
        $form = new Form(new Products);

        Admin::script('
        var _URL = window.URL || window.webkitURL;

        $(\'input[type="file"]\').change(function(e) {
            var file, img;

            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function() {
                    if(this.width > 1600 || this.height > 1600)
                    {

                        alert("圖片尺寸超過限制 :" + this.width + " x " + this.height);

                        window.location.reload();
                    }
                };

                img.src = _URL.createObjectURL(file);
            }

        });
        ');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();
        $form->multipleSelect('category_id', '類別')->options(Category::all()->pluck('name','id'))->load('model', '/api/bike_model')->saving(function ($cat) {
            return implode(",", $cat);
        });
        $form->multipleSelect('model', '車型')->options(BikeModel::all()->pluck('bike_model','id'))->saving(function ($cat) {
            return implode(",", $cat);
        });
        $form->select('product_id', '適用車款')->options(Products::select(DB::raw('CONCAT(product_name, " - ", COALESCE(color,""), " - ", locale) AS product, id'))->where('type', 1)->get()->pluck('product','id'));
        $form->text('product_name', '配件名稱')->required();
        $form->text('description', '描述');
        $form->text('product_code', 'URL')->required();
        $form->hidden('type', 'type')->default(2);
        $form->number('quantity', '數量');
        $form->select('currency', '幣別')->options(Currency::all()->pluck('code','id'))->required();
        $form->currency('price', '價格')->required();
        $form->select('color', '顏色')->options(['white' => 'White',
                                                'black' => 'Black',
                                                'red' => 'Red',
                                                'blue' => 'Blue',
                                                'yellow' => 'Yellow',
                                                '#ADADAD' => 'Grey',
                                                '#FFA042' => 'Orange',
                                                '#AE8F00' => 'Brown',
                                                '#00E3E3' => 'Aqua Blue',
                                                '#28FF28' => 'Green',
                                                '#FF60AF' => 'Pink',
                                                '#2894FF' => 'Sky blue',
                                                'n/a' => 'N/A']);
        $form->multipleImage('images', '圖片')->sortable()->removable()->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:5120|dimensions:min_width=100,min_height=100,max_width=1600,max_height=1600')->help('圖片尺寸不得超過 1600 X 1600')->autoUpload();
        $form->hidden('is_featured',)->default(0);
        $form->editor('specifications','規格表');
        $form->text('shipping_size','運送尺寸')->required();
        $form->editor('content', '內容');

        $form->number('priority', 'Priority');

        return $form;
    }
}
