<?php

use \CRUD\Table\Search\Checkbox as Checkbox;
use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Items    as Items;
use \CRUD\Table\Text     as Text;
use \CRUD\Table\Image    as Image;

echo $table->search(function() {
  
  Input::create('ID')
       ->sql('id = ?');

  Input::create('名稱')
       ->sql('name LIKE ?');

  Checkbox::create('角色')
          ->items(\M\AdminRole::ROLE)
          ->sql(function($val) {
            return Where::create('id IN (?)', \M\AdminRole::arr('adminId', 'role IN (?)', $val));
          });
});

echo $table->list(function($obj) {

  Text::create('ID')
      ->width(60)
      ->order('id')
      ->val($obj->id);

  Image::create('頭像')
       ->val($obj->avatar);

  Text::create('帳號')
      ->width(120)
      ->order('account')
      ->val($obj->account);

  Text::create('名稱')
      ->order('name')
      ->val($obj->name);

  Items::create('權限')
       ->width(200)
       ->val(array_map(function($role) {
         return \M\AdminRole::ROLE[$role->role];
       }, $obj->roles));

  Text::create('操作次數')
      ->width(100)
      ->val(number_format(count($obj->logs)) . '次');

  Text::create('新增時間')
      ->width(150)
      ->val($obj->createAt);

  Ctrl::create()
      ->addShow('AdminAdminShow', $obj)
      ->addEdit('AdminAdminEdit', $obj)
      ->addDelete('AdminAdminDelete', $obj);
});

echo $table->pages();