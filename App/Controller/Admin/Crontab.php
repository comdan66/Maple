<?php

use \CRUD\Table       as Table;
use \CRUD\Table\Order as Order;
use \CRUD\Form        as Form;
use \CRUD\Show        as Show;

class Crontab extends AdminController {
  
  public function __construct() {
    parent::__construct(\M\AdminRole::ROLE_ROOT);

    ifErrorTo('AdminCrontabIndex');

    $this->methodIn('show', 'read', function() {
      return $this->obj = \M\Crontab::one('id = ?', Router::param('id'));
    });

    $this->view->with('title', '排程執行紀錄')
               ->with('currentMenuUrl', Url::router('AdminCrontabIndex'));
  }

  public function index() {
    return $this->view->with('table', Table::create('\M\Crontab'));
  }

  public function show() {
    $show = Show::create($this->obj)
                ->setBackRouter('AdminCrontabIndex');
    
    return $this->view->with('show', $show);
  }

  public function read() {
    ifApiError(function() { return ['messages' => func_get_args()]; });
    
    $params = Input::ValidPost(function($params) {
      Validator::must($params, 'isRead', '已讀')->inEnum(array_keys(\M\Crontab::IS_READ));
      return $params;
    });
    
    transaction(function() use (&$params) {
      return $this->obj->setColumns($params)
          && $this->obj->save();
    });

    return $params;
  }
}
