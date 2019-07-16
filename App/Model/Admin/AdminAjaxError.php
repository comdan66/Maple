<?php

namespace M;

class AdminAjaxError extends Model {
  const IS_READ_YES = 'yes';
  const IS_READ_NO  = 'no';
  const IS_READ = [
    self::IS_READ_YES => '已讀', 
    self::IS_READ_NO  => '未讀',
  ];
  
  static $relations = [
    'admin' => '-> Admin'
  ];
}