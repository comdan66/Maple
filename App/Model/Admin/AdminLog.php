<?php

namespace M;

class AdminLog extends Model {
  const METHOD_GET    = 'get';
  const METHOD_POST   = 'post';
  const METHOD_PUT    = 'put';
  const METHOD_DELETE = 'delete';
  const METHOD_OTHER  = 'other';

  const METHOD = [
    self::METHOD_GET    => 'GET', 
    self::METHOD_POST   => 'POST',
    self::METHOD_PUT    => 'PUT',
    self::METHOD_DELETE => 'DELETE',
    self::METHOD_OTHER  => '其他',
  ];

  public static function create($attrs = []) {
    $method = strtolower(\Router::requestMethod());
    $method = array_key_exists($method, AdminLog::METHOD) ? $method : AdminLog::METHOD_OTHER;

    return parent::create(array_merge([
      'adminId' => Admin::current()->id,
      'method' => $method,
      'url' => \Url::current(),
      'get' => json_encode(\Input::get()),
      'post' => json_encode(\Input::post()),
      'file' => json_encode(\Input::file()),
    ], $attrs));
  }
}