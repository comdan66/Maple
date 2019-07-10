<?php

class Auth extends Controller {
  public function __construct() {
    Load::systemLib('Asset');
    Load::systemLib('Session');
  }

  public function login() {
    $flash = Session::getFlashData('flash');
    return View::create('Admin/Auth/login.php')
               ->with('flash', $flash);
  }

  public function logout() {
    Session::unsetData('admin');
    return Url::refreshWithSuccessFlash(Url::router('AdminAuthLogin'), '登出成功！');
  }

  public function signin() {
    ifErrorTo('AdminAuthLogin');

    $params = Input::ValidPost(function($params) use (&$admin) {
      Validator::must($params, 'account', '帳號')->isString(1, 190);
      Validator::must($params, 'password', '密碼')->isString(1, 190);

      $admin = \M\Admin::one('account = ?', $params['account']);
      $admin || error('此帳號不存在！');
      password_verify($params['password'], $admin->password) || error('密碼錯誤！');

      return $params;
    });

    transaction(function() use (&$admin) {
      return $admin->save();
    });

    Session::setData('admin', $admin);
    \M\AdminLog::create();
    return Url::refreshWithSuccessFlash(Url::router('AdminMainIndex'), '登入成功！');
  }
}
