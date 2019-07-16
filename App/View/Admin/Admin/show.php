<?php

use \CRUD\Show\Id       as Id;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Items    as Items;
use \CRUD\Show\Image    as Image;
use \CRUD\Show\Datetime as Datetime;

use \CRUD\Table\Search\Input    as Input;
use \CRUD\Table\Search\Checkbox as Checkbox;

use \CRUD\Table\Id       as TableId;
use \CRUD\Table\Ctrl     as TableCtrl;
use \CRUD\Table\Text     as TableText;
use \CRUD\Table\Datetime as TableDatetime;

echo $show->back();

echo $show->panel(function($obj) {
  
  Id::create();

  Image::create('頭像')
       ->val($obj->avatar);

  Text::create('名稱')
      ->val($obj->name);

  Items::create('角色')
       ->val(array_map(function($role) {
         return \M\AdminRole::ROLE[$role->role];
       }, $obj->roles));

  Datetime::create('新增時間')
      ->val($obj->createAt);
});

echo $table->search(function(&$title) {
  $title = '操作記錄';

  Input::create('ID')
       ->sql('id = ?');

  Checkbox::create('Method')
          ->sql('method IN (?)')
          ->items(\M\AdminLog::METHOD);
  
  Input::create('網址')
       ->sql('url LIKE ?');
});

echo $table->list(function($obj) use ($show) {

  TableId::create();

  TableText::create('Method')
           ->width(90)
           ->val(\M\AdminLog::METHOD[$obj->method]);

  TableText::create('網址')
           ->val($obj->url);

  TableDatetime::create('新增時間')
               ->align('right')
               ->val($obj->createAt);

  TableCtrl::create()
      ->setShowRouter('AdminAdminLogShow', $show->obj(), $obj);
});

echo $table->pages();