<?php

use \CRUD\Show\Text     as Text;

echo $show->back();

echo $show->panel(function($obj, &$title) {
  
  Text::create('ID')
      ->val($obj->id);

  Text::create('類型')
      ->val(\M\Backup::TYPE[$obj->type]);

  Text::create('下載')
      ->val(\HTML\A::create('下載')->href($obj->file->url())->download((string)$obj->file));

  Text::create('大小')
      ->val(implode(' ', memoryUnit($obj->size)));

  Text::create('狀態')
      ->val(\M\Backup::STATUS[$obj->status]);

  Text::create('新增時間')
      ->val($obj->createAt);
});
