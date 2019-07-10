<?php

use \CRUD\Table\Search\Input    as Input;

use \CRUD\Table\Ctrl     as Ctrl;
use \CRUD\Table\Switcher as Switcher;
use \CRUD\Table\Text     as Text;

echo $table->search(function() {

  Input::create('ID')
       ->sql('id = ?');

  Input::create('操作者名稱')
       ->sql(function($val) {
         return Where::create('adminId IN (?)', array_column(\M\Admin::all([
           'select' => 'id',
           'where' => ['name LIKE ?', '%' . $val . '%']
         ]), 'id'));
       });
  
  Input::create('錯誤訊息')
       ->sql('content LIKE ?');
});

echo $table->list(function($obj) {

  Switcher::create('已讀')
          ->on(\M\AdminAjaxError::IS_READ_YES)
          ->off(\M\AdminAjaxError::IS_READ_NO)
          ->url(Url::router('AdminAdminAjaxErrorRead', $obj))
          ->column('isRead')
          ->label('adminAjax-isRead');

  Text::create('ID')
      ->width(60)
      ->order('id')
      ->val($obj->id);

  Text::create('操作者名稱')
      ->width(200)
      ->val($obj->admin ? $obj->admin->name : null);

  Text::create('錯誤訊息')
      ->val($obj->content);

  Text::create('新增時間')
      ->width(150)
      ->val($obj->createAt);

  Ctrl::create()
      ->addShow('AdminAdminAjaxErrorShow', $obj);
});

echo $table->pages();