<?php

use \CRUD\Show\Json     as Json;
use \CRUD\Show\Text     as Text;

echo $show->back();

echo $show->panel(function($obj, &$title) {
  
  Text::create('ID')
      ->val($obj->id);

  Text::create('Method')
      ->val($obj->method);

  Text::create('標題')
      ->val($obj->title);

  Json::create('參數')
      ->val($obj->params);

  Text::create('耗時')
      ->val(number_format($obj->rTime, 4) . ' 秒');

  Text::create('狀態')
      ->val(\M\Crontab::STATUS[$obj->status]);

  Text::create('新增時間')
      ->val($obj->createAt);
});
