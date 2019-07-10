<?php

use \CRUD\Show\Image    as Image;
use \CRUD\Show\Text     as Text;
use \CRUD\Show\Items    as Items;

use \CRUD\Table\Search\Checkbox as Checkbox;
use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Ctrl     as TableCtrl;
use \CRUD\Table\Text     as TableText;

echo $show->back();

echo $show->panel(function($obj) {
  
  Text::create('ID')
      ->val($obj->id);

  Image::create('頭像')
       ->val($obj->avatar);

  Text::create('名稱')
      ->val($obj->name);

  Items::create('角色')
       ->val(array_map(function($role) {
         return \M\AdminRole::ROLE[$role->role];
       }, $obj->roles));

  Text::create('新增時間')
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

  TableText::create('ID')
           ->width(60)
           ->order('id')
           ->val($obj->id);

  TableText::create('Method')
           ->width(80)
           ->val($obj->method);

  TableText::create('網址')
           ->val($obj->url);

  TableText::create('新增時間')
           ->width(150)
           ->val($obj->createAt);

  TableCtrl::create()
      ->addShow('AdminAdminLogShow', $show->obj(), $obj);
});

echo $table->pages();
