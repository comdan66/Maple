<?php

namespace M;

class AdminLog extends Model {
  const METHOD_GET    = 'get';
  const METHOD_POST   = 'post';
  const METHOD_PUT    = 'put';
  const METHOD_DELETE = 'delete';
  const METHOD_OTHER  = 'other';

  const METHOD = [
    self::METHOD_GET    => 'Get', 
    self::METHOD_POST   => 'Post',
    self::METHOD_PUT    => 'Put',
    self::METHOD_DELETE => 'Delete',
    self::METHOD_OTHER  => 'Other',
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
