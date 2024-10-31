<?php
use Dcat\Admin\Admin;
use Dcat\Admin\Grid;
use Dcat\Admin\Form;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Show;

Admin::script(
    <<<JS

    $(document).ready(function () {
        const timeout = 1800000;  // 900000 ms = 15 minutes
        var idleTimer = null;
        $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
            clearTimeout(idleTimer);

            idleTimer = setTimeout(function () {
                $('.icon-power').trigger('click');
            }, timeout);
        });
        $("body").trigger("mousemove");
    });
JS            
);
Grid::resolving(function (Grid $grid) {
    $grid->enableDialogCreate();
    $grid->showQuickEditButton();
    $grid->disableFilterButton();
    $grid->disableEditButton();
    $grid->disableViewButton();
    $grid->setDialogFormDimensions('60%', '95%');
});

Form::resolving(function (Form $form) {
    $form->disableEditingCheck();

    $form->disableCreatingCheck();

    $form->disableViewCheck();

    $form->tools(function (Form\Tools $tools) {
         $tools->disableDelete();
         $tools->disableView();
    });

});
