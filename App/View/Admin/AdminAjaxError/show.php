<?php

use \CRUD\Show\Id       as Id;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Json     as Json;
use \CRUD\Show\Datetime as Datetime;

echo $show->back();

echo $show->panel(function($obj) {
  
  Id::create();

  Text::create('操作者名稱')
      ->val($obj->admin ? $obj->admin->name : null);

  Datetime::create('新增時間')
          ->val($obj->createAt);
});

echo $show->panel(function($obj, &$title) {
  $title = '錯誤訊息';

  Json::create()
      ->val($obj->content);
});