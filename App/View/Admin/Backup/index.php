<?php

use \CRUD\Table\Search\Checkbox as Checkbox;
use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Text     as Text;

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
          ->url(Url::router('AdminBackupRead', $obj))
          ->column('isRead')
          ->label('backup-isRead');

  Text::create('ID')
      ->width(60)
      ->order('id')
      ->val($obj->id);

  Text::create('類型')
      ->val(\M\Backup::TYPE[$obj->type]);

  Text::create('下載')
      ->width(80)
      ->align('right')
      ->val(\HTML\A::create('下載')->href($obj->file->url())->download((string)$obj->file));

  Text::create('大小')
      ->width(120)
      ->order('size')
      ->align('right')
      ->val(implode(' ', memoryUnit($obj->size)));

  Text::create('狀態')
      ->width(80)
      ->order('status')
      ->align('right')
      ->val(\HTML\Span::create(\M\Backup::STATUS[$obj->status])->class($obj->status == \M\Backup::STATUS_SUCCESS ? 'green' : 'red'));

  Text::create('新增時間')
      ->width(150)
      ->align('right')
      ->val($obj->createAt);

  Ctrl::create()
      ->addShow('AdminBackupShow', $obj);
});

echo $table->pages();