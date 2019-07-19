<?php

use \CRUD\Table\Search\Input as Input;

use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Text     as Text;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Datetime as Datetime;

echo $table->search(function() {

  Input::create('ID')
       ->sql('id = ?');

  Input::create('操作者名稱')
       ->sql(function($val) { return Where::create('adminId IN (?)', array_column(\M\Admin::all(['select' => 'id', 'where' => ['name LIKE ?', '%' . $val . '%']]), 'id')); });
  
  Input::create('錯誤訊息')
       ->sql('content LIKE ?');
});

echo $table->list(function($obj) {

  Switcher::create('已讀')
          ->on(\M\AdminAjaxError::IS_READ_YES)
          ->off(\M\AdminAjaxError::IS_READ_NO)
          ->router('AdminAdminAjaxErrorRead', $obj)
          ->column('isRead')
          ->label('adminAjax-isRead');

  Text::create('操作者名稱')
      ->width(200)
      ->val($obj->admin ? $obj->admin->name : null);

  Text::create('錯誤訊息')
      ->val(minText($obj->content));

  Datetime::create('新增時間')
          ->align('right')
          ->val($obj->createAt);

  Ctrl::create()
      ->setShowRouter('AdminAdminAjaxErrorShow', $obj);
});

echo $table->pages();