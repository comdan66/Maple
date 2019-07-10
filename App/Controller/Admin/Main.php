<?php

class Main extends AdminController {
  public function index() {
    return $this->view->with('currentUrl', Url::base('admin'))
                      ->with('title', '首頁');
  }

  public function ajaxErrorCreate() {
    ifApiError(function() { return ['messages' => func_get_args()]; });

    $params = Validator::post(function() {
      Validator::need('content', '內容')->isString(1);
    });

    $params['adminId'] = \M\Admin::current()->id;

    transaction(function() use (&$params) {
      return \M\AdminAjaxError::create($params);
    });

    return ['messages' => 'OK'];
  }

  public function theme() {
    ifApiError(function() { return ['messages' => func_get_args()]; });

    $params = Validator::post(function() {
      Validator::need('theme', '主題')->inEnum(array_keys(AdminController::THEME));
    });

    Session::setData('theme', $params['theme']);
    return ['messages' => 'OK'];
  }
}
