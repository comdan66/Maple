<?php

class Main extends AdminController {
  public function index() {
    return $this->view->with('currentMenuUrl', Url::base('admin'))
                      ->with('title', '首頁');
  }

  public function ajaxErrorCreate() {
    ifApiError(function() { return ['messages' => func_get_args()]; });

    $params = Input::ValidPost(function($params) {
      Validator::must($params, 'content', '內容')->isString(1);
      return $params;
    });

    $params['adminId'] = \M\Admin::current()->id;

    transaction(function() use (&$params, &$obj) {
      return $obj = \M\AdminAjaxError::create($params);
    });

    return ['id' => $obj->id];
  }

  public function theme() {
    ifApiError(function() { return ['messages' => func_get_args()]; });

    $params = Input::ValidPost(function($params) {
      Validator::must($params, 'theme', '主題')->inEnum(array_keys(AdminController::THEME));
      return $params;
    });

    Session::setData('theme', $params['theme']);
    return ['messages' => 'OK'];
  }
}
