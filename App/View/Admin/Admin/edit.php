<?php

use \CRUD\Form\Image    as Image;
use \CRUD\Form\Input    as Input;
use \CRUD\Form\Checkbox as Checkbox;

echo $form->back();

echo $form->form(function($obj) {

  Image::create('avatar', '頭像')
       ->accept('image/*')
       ->val($obj->avatar);

  Input::create('name', '名稱')
       ->must()
       ->focus()
       ->val($obj->name);

  Input::create('account', '帳號')
       ->must()
       ->val($obj->account);

  Input::create('password', '密碼')
       ->type('password')
       ->val('');

  Checkbox::create('roles', '特別權限')
          ->items(\M\AdminRole::ROLE)
          ->val(array_column($obj->roles, 'role'));
});