<?php

namespace App\Admin\Controllers;

use App\Models\Index;
use App\Http\Controllers\Controller;
use Dcat\Admin\Http\Controllers\HasResourceActions;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;

class IndexController extends Controller
{
    use HasResourceActions;

    /*
     * define areas and recommended image sizes
     * in future may have more uses for area specifications or integration w config
     */
    protected $areas = [
        'splash_area' => [
            'image' => [
                'w' => 1200,
                'h' => 675
            ]
        ],
        'register_area' => [
            'image' => [
                'w' => 1000,
                'h' => 700
            ]
        ],
        'warranty_area' => [
            'image' => [
                'w' => 1000,
                'h' => 700
            ]
        ],
        'dealer_area' => [
            'image' => [
                'w' => 1920,
                'h' => 900
            ]
        ],
        'about_area' => [
            'image' => [
                'w' => 1920,
                'h' => 900
            ]
        ]
    ];

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('首頁管理')
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
            ->body($this->form($id)->edit($id));
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
        $grid = new Grid(new Index);
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('locale', '語系', ['en' => 'En', 'zh-TW' => 'zh-TW']);
        });

        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disablePagination();
        $grid->disableRowSelector();

        $grid->area('位置');
        $grid->background_img('背景圖片')->image();
        $grid->column('title', '標題');
        $grid->subtitle('副標題');
        $grid->content('內容')->width(500);
        $grid->locale('語系');

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        if($id!=null){
            $data = Index::where('id', $id)->first();
        }
        $form = new Form(new Index);
        if ($form->isEditing()){
            $form->tools(function (Form\Tools $tools) {
                // Disable `Delete` btn.
                $tools->disableDelete();
            });
        }
        $form->display('area', '位置')->required();
        $form->image('background_img', '背景圖片')->rules('image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048|dimensions:min_width=100,min_height=100,max_width=1920,max_height=1920')->autoUpload();
        if(isset($data)){
            $size = $this->getRecommendedImageSize($data->area);
            $form->html('<small>最適尺寸 '.$size['size'].' ('.$size['ratio'].')</small>');
            $form->html('<small>上傳檔案大小上限 2MB, 最大尺寸 1920px x 1920px</small>');
        }
        $form->text('title', '標題');
        $form->text('subtitle', '副標題');
        $form->textarea('content', '內容');
        $form->url('link', '連結');
        $form->select('locale', '語系')->options(['en' => 'En', 'zh-TW' => 'zh-TW'])->required();

        return $form;
    }

    protected function getRecommendedImageSize($area = null){
        if($area==null){
            return [
                'size' => '1200px x 1200px',
                'ratio' => '1:1'
            ];
        } else {
            $area_detail = $this->areas[$area];
            $r=$area_detail['image']['w']/$area_detail['image']['h'];
            $ratio = sprintf("%s:1",number_format($r, 1));
            return [
                'size' => $area_detail['image']['w']."px x ".$area_detail['image']['h']."px",
                'ratio' => $ratio
            ];
        }

    }
}
