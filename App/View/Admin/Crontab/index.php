<?php

use \CRUD\Table\Search\Checkbox as Checkbox;
use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Text     as Text;

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
          ->url(Url::router('AdminCrontabRead', $obj))
          ->column('isRead')
          ->label('crontab-isRead');

  Text::create('ID')
      ->width(60)
      ->order('id')
      ->val($obj->id);

  Text::create('Method')
      ->width(140)
      ->val($obj->method);

  Text::create('標題')
      ->val($obj->title);

  Text::create('參數')
      ->width(120)
      ->align('right')
      ->val(minText($obj->params));

  Text::create('耗時')
      ->width(120)
      ->order('rTime')
      ->align('right')
      ->val(number_format($obj->rTime, 4) . ' 秒');

  Text::create('狀態')
      ->width(80)
      ->order('status')
      ->align('right')
      ->val(\HTML\Span::create(\M\Crontab::STATUS[$obj->status])->class($obj->status == \M\Crontab::STATUS_SUCCESS ? 'green' : 'red'));

  Text::create('新增時間')
      ->width(150)
      ->align('right')
      ->val($obj->createAt);

  Ctrl::create()
      ->addShow('AdminCrontabShow', $obj);
});

echo $table->pages();