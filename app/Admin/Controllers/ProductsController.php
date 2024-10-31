<?php

namespace App\Admin\Controllers;

use App\Models\Products;
use App\Models\Category;
use App\Models\BikeModel;
use App\Models\Currency;
use App\Models\Store;
use App\Models\Area;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;

class ProductsController extends Controller
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
            ->header('車輛')
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
        $grid->model()->where('type', 1);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });
        Admin::script('
            $(".grid-modal .modal-dialog").css("width", "80%");
            $(".modal-body").css("overflow-x", "scroll");
        ');

        $grid->product_name('商品名稱');
        $grid->product_code('URL');
        $grid->column('category_id', '類別')->display(function(){
            if($this->category)
                return "<span class='label label-primary'>".$this->category->name."</span>";

            return '';
        });
        $grid->column('model','車型')->display( function(){
            if($this->bike_model)
                return "<span class='label label-primary'>".$this->bike_model->bike_model."</span>";

            return '';
        });
        $grid->quantity('數量');
        $grid->price('價格')->display(function($price){
            $currencies = $this->currencies->code ?? '';
            return $currencies.' '.$price;
        });
        $grid->color('顏色')->label('primary');
        $grid->images('圖片')->image();
        $grid->priority('priority')->sortable();
        $grid->is_featured('推薦商品')->display( function($featured){
            if ($featured == 1){
                return "<span class='label label-success'>Featured</span>";
            }
            else{
                return "<span class='label label-primary'>Not Featured</span>";
            }
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

        $form->multipleSelect('category_id', '類別')->options(Category::all()->pluck('name','id'))->saving(function ($cat) {
            return implode(",", $cat);
        });
        //$form->select('category_id', '類別')->options(Category::all()->pluck('name','id'))->load('model', '/api/bike_model')->required();
        $form->select('model', '車型')->options(BikeModel::all()->pluck('bike_model','id'))->required();
        $form->text('product_name', '商品名稱')->required();
        $form->text('description', '商品描述');
        $form->textarea('Head', 'Head內容');
        $form->text('product_code', 'URL')->required();
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
                                                  'n/a' => 'N/A'])->required();
        $form->multipleImage('images', '圖片')->removable()->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:5120|dimensions:min_width=100,min_height=100,max_width=1600,max_height=1600')->help('圖片尺寸不得超過 1600 X 1600')->autoUpload();
        $form->switch('is_featured', '特色商品');
        $form->hidden('type', 'type')->default(1);
        $form->text('shipping_size','運送尺寸')->required();
        $form->multipleSelect('accessories', '配件')->options(Products::where('type', 2)->get()->pluck('product_name','id'));
        $form->table('vendors', '代理商', function ($table) {
            $table->select('country', '國家')->options(Area::all()->pluck('AreaEngName','AreaEngName'));
            $table->text('store', '名稱');
            $table->email('email');
            $table->text('phone', '電話');
            $table->text('address','地址');
        });
        $form->editor('content', '內容');
        $form->editor('specifications','規格表');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();
        $form->number('priority', 'Priority');

        return $form;
    }
}
