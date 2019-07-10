<?php

use \CRUD\Show\Text     as Text;
use \CRUD\Show\Json     as Json;

echo $show->back();

echo $show->panel(function($obj) {
  
  Text::create('ID')
      ->val($obj->id);

  Text::create('操作者名稱')
      ->val($obj->admin ? $obj->admin->name : null);

  Json::create('錯誤訊息')
      ->val($obj->content);

  Text::create('新增時間')
      ->val($obj->createAt);
});
