<?php

use \CRUD\Table\Search\Input    as Input;
use \CRUD\Table\Search\Checkbox as Checkbox;

use \CRUD\Table\Id       as Id;
use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Text     as Text;
use \CRUD\Table\Image    as Image;
use \CRUD\Table\Items    as Items;
use \CRUD\Table\Number   as Number;
use \CRUD\Table\Datetime as Datetime;
use \CRUD\Table\EditText as EditText;

echo $table->search(function() {
  
  Input::create('ID')
       ->sql('id = ?');

  Input::create('名稱')
       ->sql('name LIKE ?');

  Input::create('帳號')
       ->sql('account LIKE ?');

  Checkbox::create('權限')
          ->items(\M\AdminRole::ROLE)
          ->sql(function($val) {
            return Where::create('id IN (?)', array_column(\M\AdminRole::all(['select' => 'adminId', 'where' => ['role = ?', $val]]), 'adminId'));
          });
});

echo $table->list(function($obj) {

  Id::create();

  Image::create('頭像')
       ->val($obj->avatar);

  Text::create('帳號')
      ->width(120)
      ->order('account')
      ->val($obj->account);

  EditText::create('名稱')
          ->must()
          ->order('name')
          ->column('name')
          ->router('AdminAdminColumnName', $obj)
          ->val($obj->name);

  Items::create('權限')
       ->width(200)
       ->align('right')
       ->val(array_map(function($role) {
         return \M\AdminRole::ROLE[$role->role];
       }, $obj->roles));

  Number::create('操作次數')
        ->unit('次')
        ->width(100)
        ->align('right')
        ->val($obj->logs);

  Datetime::create('新增時間')
          ->align('right')
          ->val($obj->createAt);

  Ctrl::create()
      ->setShowRouter('AdminAdminShow', $obj)
      ->setEditRouter('AdminAdminEdit', $obj)
      ->setDeleteRouter('AdminAdminDelete', $obj);
});

echo $table->pages();