<?php

namespace App\Admin\Controllers;

use App\Models\Store;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class StoreController extends Controller
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
            ->header('銷售據點')
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
        $grid = new Grid(new Store);

        $grid->name('店名');
        $grid->country('國家');
        $grid->phone('電話');
        $grid->email('email');
        $grid->address('地址');
        $grid->website('網址');
        $grid->business_hour('營業時間');

        $grid->filter(function($filter){

            $filter->disableIdFilter();

            $filter->like('name', '店名');
            $filter->like('address', '地址');

        });
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
        $form = new Form(new Store);

        $form->text('name', '店名')->required();
        $form->text('NickName', '暱稱')->required();
        $form->text('CompanyNo', '國家編號')->required();
        $form->text('country', '國家')->required();
        $form->text('address', '地址')->required();
        $form->text('phone', '電話')->required();
        $form->url('website', '網址');
        $form->email('email', 'email');
        $form->text('business_hour', '營業時間');
        $form->text('Long', '經度');
        $form->text('Lat', '緯度');

        return $form;
    }
}
