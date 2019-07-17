<?php

use \CRUD\Table\Search\Input    as Input;
use \CRUD\Table\Search\Checkbox as Checkbox;

use \CRUD\Table\Tag      as Tag;
use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Text     as Text;
use \CRUD\Table\Number   as Number;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Datetime as Datetime;

echo $table->search(function() {

  Input::create('ID')
       ->sql('id = ?');

  Input::create('標題')
       ->sql('title LIKE ?');

  Input::create('Method')
       ->sql('method LIKE ?');

  Checkbox::create('狀態')
          ->items(\M\Crontab::STATUS)
          ->sql('status IN (?)');

  Checkbox::create('已讀')
          ->items(\M\Crontab::IS_READ)
          ->sql('isRead IN (?)');
});

echo $table->list(function($obj) {
  
  Switcher::create('已讀')
          ->on(\M\Crontab::IS_READ_YES)
          ->off(\M\Crontab::IS_READ_NO)
          ->router('AdminCrontabRead', $obj)
          ->column('isRead')
          ->label('crontab-isRead');

  Tag::create('狀態')
     ->width(80)
     ->align('center')
     ->order('status')
     ->color($obj->status == \M\Crontab::STATUS_SUCCESS ? Tag::GREEN : Tag::RED)
     ->val(\M\Crontab::STATUS[$obj->status]);

  Text::create('Method')
      ->width(150)
      ->val($obj->method);

  Text::create('標題')
      ->val($obj->title);

  Number::create('耗時')
        ->width(120)
        ->order('rTime')
        ->align('right')
        ->unit('秒')
        ->decimal(4)
        ->val($obj->rTime);

  Datetime::create('新增時間')
          ->align('right')
          ->val($obj->createAt);

  Ctrl::create()
      ->setShowRouter('AdminCrontabShow', $obj);
});

echo $table->pages();