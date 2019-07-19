<?php

use \CRUD\Table\Search\Checkbox as Checkbox;
use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Id       as Id;
use \CRUD\Table\Tag      as Tag;
use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Text     as Text;
use \CRUD\Table\Number   as Number;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Datetime as Datetime;

echo $table->search(function() {
  
  Input::create('ID')
       ->sql('id = ?');

  Input::create('日期')
       ->type('date')
       ->sql('DATE(createAt) = ?');

  Input::create('大於(Byte)')
       ->type('number')
       ->sql('size >= ?');

  Checkbox::create('類型')
          ->items(\M\Backup::TYPE)
          ->sql('type IN (?)');

  Checkbox::create('狀態')
          ->items(\M\Backup::STATUS)
          ->sql('status IN (?)');

  Checkbox::create('已讀')
          ->items(\M\Backup::IS_READ)
          ->sql('isRead IN (?)');
});

echo $table->list(function($obj) {

  Switcher::create('已讀')
          ->on(\M\Backup::IS_READ_YES)
          ->off(\M\Backup::IS_READ_NO)
          ->router('AdminBackupRead', $obj)
          ->column('isRead')
          ->label('backup-isRead');

  Tag::create('狀態')
     ->width(80)
     ->align('center')
     ->order('status')
     ->color($obj->status == \M\Backup::STATUS_SUCCESS ? Tag::GREEN : Tag::RED)
     ->val(\M\Backup::STATUS[$obj->status]);

  Text::create('類型')
      ->val(\M\Backup::TYPE[$obj->type]);

  $size = memoryUnit($obj->size);
  Number::create('大小')
        ->width(120)
        ->order('size')
        ->align('right')
        ->unit($size[1] ?? '')
        ->val($size[0] ?? '');

  Text::create('下載')
      ->width(80)
      ->align('right')
      ->val(\HTML\A::create('下載')->href($obj->file->url())->download((string)$obj->file));

  Datetime::create('新增時間')
          ->align('right')
          ->val($obj->createAt);

  Ctrl::create()
      ->setShowRouter('AdminBackupShow', $obj);
});

echo $table->pages();