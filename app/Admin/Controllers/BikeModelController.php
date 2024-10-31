<?php

namespace App\Admin\Controllers;

use App\Models\Bike;
use App\Models\BikeModel;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use App\Models\Category;

class BikeModelController extends Controller
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
            ->header('車型')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BikeModel());
        $grid->column('category_id', '類別')->display(function(){
            return $this->category->name;
        });
        $grid->bike_model('車型')->sortable();
        $grid->show_on_home('show on store');
        $grid->priority('priority')->sortable();
        $grid->locale('語系')->using(['en' => 'En', 'zh-TW' => 'zh-TW']);

        $grid = $this->sortGridByDate($grid);

        $grid->disableRowSelector();

        $grid->disableColumnSelector();

        $grid->filter(function($filter){

            $filter->disableIdFilter();

            //$filter->like('category_id', '類別')->select(Category::all()->pluck('name', 'id'));

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
        $form = new Form(new BikeModel);

        $form->select('category_id', '類別')->options(Category::all()->pluck('name', 'id'))->required();
        $form->text('bike_model', '車型');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();
        $form->text('slug', 'Slug');
        $form->switch('show_on_home', 'Show on shop page');
        $form->number('priority', 'priority');

        return $form;
    }
}
