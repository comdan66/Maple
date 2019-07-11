<?php

use \CRUD\Table       as Table;
use \CRUD\Table\Order as Order;
use \CRUD\Form        as Form;
use \CRUD\Show        as Show;

class AdminLog extends AdminController {
  
  public function __construct() {
    parent::__construct();

    ifErrorTo('AdminAdminIndex');

    $this->methodIn(function() {
      return $this->parent = \M\Admin::one('id = ?', Router::param('adminId'));
    });

    Router::aliasAppendParam('AdminAdminLog', $this->parent);

    ifErrorTo('AdminAdminShow', $this->parent);

    $this->methodIn('show', function() {
      return $this->obj = \M\AdminLog::one('id = ?', Router::param('id'));
    });

    $this->view->with('title', ['管理員管理', '紀錄管理'])
               ->with('currentUrl', Url::router('AdminAdminIndex'));
  }

  public function show() {
    $show = Show::create($this->obj)
                ->setBackUrl(Url::router('AdminAdminShow', $this->parent), '回列表');
    
    return $this->view->with('show', $show);
  }
}
