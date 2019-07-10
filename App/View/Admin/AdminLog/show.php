<?php

use \CRUD\Show\Text     as Text;
use \CRUD\Show\Json     as Json;

echo $show->back();

echo $show->panel(function($obj) {
  
  Text::create('ID')
      ->val($obj->id);

  Text::create('Method')
      ->val($obj->method);

  Text::create('網址')
      ->val($obj->url);

  Json::create('GET 資料')
      ->val($obj->get);

  Json::create('POST 資料')
      ->val($obj->post);

  Json::create('FILE 資料')
      ->val($obj->file);

  Json::create('FLEASH 資料')
      ->val($obj->flash);

  Text::create('新增時間')
      ->val($obj->createAt);
});