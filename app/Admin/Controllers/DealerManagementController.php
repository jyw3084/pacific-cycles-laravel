<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\Dealers;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;

class DealerManagementController extends Controller
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
            ->body(
                $this->grid(),
                Admin::js('/js/maps.js')
            );
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
            // ->js('')
            ->description('列表')
            ->body(
                $this->form(),
                Admin::js('/js/maps.js'),
                )
            ;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $user = User::where('user_type', 3);
        // $grid = new Grid(new (User::where('user_type', 3)));
        $grid = new Grid(new Dealers);

        $grid->quickSearch('name');
        // $grid->showQuickEditButton();
        // $grid->enableDialogCreate();
        $grid->column('id')->sortable();
        $grid->column('name')->sortable();
        $grid->column('email')->sortable();
        $grid->column('active')->switch(['green', $refresh = true]);

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Dealers::findOrFail($id));

        $show->id('ID');
        $show->ssdsd('ssdsd');
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        Admin::js(asset('/js/leaflet/leaflet.js'));
        Admin::js('https://unpkg.com/leaflet@1.7.1/dist/leaflet.js');
        Admin::css('https://unpkg.com/leaflet@1.3.1/dist/leaflet.css');
        Admin::css('https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css');
        Admin::css('https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css');
        Admin::style(
            <<<CSS
                #mapid {
                    height: 400px !important;
                    border: 1px solid #888888;
                }
            CSS
        );
        $form = new Form(new Dealers);

        $form->hidden('id');
        $form->text('name');
        $form->text('contact_number');
        $form->text('address');
        $form->email('email');
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);
        $form->hidden('user_type');
        // $form->leaflet('latitude');
        $form->saving(function ($form) {
            // print_r($form->id);die;
            $form->user_type = 3;
            // print_r($form->bikes);die;
            if ($form->password && $form->model()->get('password') != $form->password) {
                $form->password = bcrypt($form->password);
            }

        });


        return $form;
    }
}
