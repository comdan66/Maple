<?php

use \CRUD\Show\Id       as Id;
use \CRUD\Show\Tag      as Tag;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Number   as Number;
use \CRUD\Show\Datetime as Datetime;

echo $show->back();

echo $show->panel(function($obj) {
  
  Id::create();

  Tag::create('狀態')
     ->color($obj->status == \M\Backup::STATUS_SUCCESS ? Tag::GREEN : Tag::RED)
     ->val(\M\Backup::STATUS[$obj->status]);

  Text::create('類型')
      ->val(\M\Backup::TYPE[$obj->type]);

  $size = memoryUnit($obj->size);
  Number::create('大小')
        ->unit($size[1])
        ->val($size[0]);

  Text::create('檔案')
      ->val(\HTML\A::create($obj->file)->href($obj->file->url())->download((string)$obj->file));

  Datetime::create('新增時間')
          ->val($obj->createAt);
});