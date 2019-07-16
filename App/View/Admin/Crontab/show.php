<?php

use \CRUD\Show\Id       as Id;
use \CRUD\Show\Tag      as Tag;
use \CRUD\Show\Json     as Json;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Number   as Number;
use \CRUD\Show\Datetime as Datetime;

echo $show->back();

echo $show->panel(function($obj) {
  
  Id::create();

  Tag::create('狀態')
     ->color($obj->status == \M\Crontab::STATUS_SUCCESS ? Tag::GREEN : Tag::RED)
     ->val(\M\Crontab::STATUS[$obj->status]);

  Number::create('耗時')
        ->unit('秒')
        ->decimal(4)
        ->val($obj->rTime);

  Text::create('Method')
      ->val($obj->method);

  Text::create('標題')
      ->val($obj->title);

  Datetime::create('新增時間')
          ->val($obj->createAt);
});

echo $show->panel(function($obj, &$title) {
  $title = '參數';

  Json::create()
      ->val($obj->params);
});