<?php

use \CRUD\Form\Image    as Image;
use \CRUD\Form\Input    as Input;
use \CRUD\Form\Checkbox as Checkbox;

echo $form->back();

echo $form->form(function() {
  
  Image::create('avatar', '頭像')
       ->accept('image/*');

  Input::create('name', '名稱')
       ->need()
       ->focus();

  Input::create('account', '帳號')
       ->need();

  Input::create('password', '密碼')
       ->need()
       ->type('password');

  Checkbox::create('roles', '特別權限')
          ->items(\M\AdminRole::ROLE);
});



