<?php

namespace M;

class Admin extends Model {

  static $relations = [
    'roles' => 'AdminRole',
    'logs' => 'AdminLog'
  ];

  public static function current() {
    static $current;
    return !$current && (class_exists('\Session') || \Load::systemLib('Session')) && \Session::getData('admin') ? $current = Admin::one('id = ?', \Session::getData('admin')->id) : $current;
  }

  public function inRoles() {
    if (!$args = arrayFlatten(func_get_args()))
      return true;
    
    $args || $args = [AdminRole::ROLE_ROOT];
    $roles = array_column($this->roles, 'role');
    
    foreach ($args as $arg)
      if (in_array($arg, $roles))
        return true;

    return false;
  }
}

Admin::imageUploader('avatar')
     ->default(class_exists('\Asset') ? \Asset::img('Asset/img/user.png') : null)
     ->version('w100', ['resize', 100, 100, 'width'])
     ->version('c120x120', ['adaptiveResizeQuadrant', 120, 120, 'c']);