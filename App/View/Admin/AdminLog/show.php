<?php

use \CRUD\Show\Id       as Id;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Json     as Json;
use \CRUD\Show\Datetime as Datetime;

echo $show->back();

echo $show->panel(function($obj) {

  Id::create();

  Text::create('Method')
      ->val(\M\AdminLog::METHOD[$obj->method]);

  Text::create('網址')
      ->val($obj->url);

  Datetime::create('新增時間')
          ->val($obj->createAt);
});

echo $show->panel(function($obj, &$title) {
  $title = '參數';

  Json::create('GET 資料')
      ->val($obj->get);

  Json::create('POST 資料')
      ->val($obj->post);

  Json::create('FILE 資料')
      ->val($obj->file);

  Json::create('FLASH 資料')
      ->val($obj->flash);
});