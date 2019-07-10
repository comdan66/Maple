<?php

use \CRUD\Table       as Table;
use \CRUD\Table\Order as Order;
use \CRUD\Form        as Form;
use \CRUD\Show        as Show;

class Crontab extends AdminController {
  
  public function __construct() {
    parent::__construct(\M\AdminRole::ROLE_ROOT);

    ifErrorTo('AdminCrontabIndex');

    $this->methedIn('show', 'read', function() {
      return $this->obj = \M\Crontab::one('id = ?', Router::param('id'));
    });

    $this->view->with('title', '排程執行紀錄')
               ->with('currentUrl', Url::router('AdminCrontabIndex'));
  }

  public function index() {
    return $this->view->with('table', Table::create('\M\Crontab'));
  }

  public function show() {
    $show = Show::create($this->obj)
                ->setBackUrl(Url::router('AdminCrontabIndex'), '回列表');
    
    return $this->view->with('show', $show);
  }

  public function read() {
    ifApiError(function() { return ['messages' => func_get_args()]; });
    
    $params = Validator::post(function() {
      Validator::need('isRead', '已讀')->inEnum(array_keys(\M\Crontab::IS_READ));
    });
    
    transaction(function() use (&$params) {
      return $this->obj->setColumns($params)
          && $this->obj->save();
    });

    return $params;
  }
}
