<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sortGridByDate($grid, $show=true){
        //order default by created_at and show column for date
        $grid->model()->orderByDesc('created_at');
        //show created at column
        if($show) {
            $grid->column('created_at', '建立日期')->sortByDesc('created_at')->display(function () {
                return date('Y-m-d H:i', strtotime($this->created_at));
            })->sortable();
        }

        return $grid;
    }
}
