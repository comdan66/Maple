<?php

abstract class AdminCkeditorController extends AdminCrudController {
  public function __construct() {
    parent::__construct(func_get_args());

    $this->asset->removeCSS('/Asset/css/Admin/Layout.css')
                ->addCSS('/Asset/css/Admin/LayoutCkeditor.css')
                ->addJS('/Asset/js/Admin/LayoutCkeditor.js');

    $this->view->appendTo(View::create('Admin/LayoutCkeditor.php'), 'content');
  }
}
