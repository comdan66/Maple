<?php

Load::systemLib('Html');
Load::systemFunc('File');

abstract class CRUD {
  protected $back;

  public function back() {
    return $this->back ? '<div class="panel"><div class="back">' . $this->back . '</div></div>' : '';
  }

  public function setBackUrl($href, $text = '回列表') {
    $this->back = '<a href="' . CRUD::getBackOffsetLimit($href) . '">' . $text . '</a>';
    return $this;
  }

  public function setBackRouter($router) {
    return $this->setBackUrl(call_user_func_array('\Url::router', func_get_args()));
    return $this;
  }

  public static function getBackOffsetLimit($href) {
    if ($params = parse_url($href, PHP_URL_QUERY)) {
      $limitKey  = \CRUD\Table\Pagination::$limitKey;
      $offsetKey = \CRUD\Table\Pagination::$offsetKey;

      $param1s = array_map(function($param) { list($key, $val) = array_pad(explode('=', $param), 2, null); return [$key, $val]; }, array_filter(explode('&', $params), function($param) { return $param !== ''; }));
      $param2s = array_map(function($param) { list($key, $val) = array_pad(explode('=', $param), 2, null); return [$key, $val]; }, array_filter(CRUD::backOffsetLimit(false), function($param) { return $param !== ''; }));
      
      $params = [];
      foreach ($param1s as $param1) array_key_exists($param1[0], $params) || $params[$param1[0]] = $param1[1];
      foreach ($param2s as $param2) array_key_exists($param2[0], $params) || $params[$param2[0]] = $param2[1];
    } else {
      $param2s = array_map(function($param) { list($key, $val) = array_pad(explode('=', $param), 2, null); return [$key, $val]; }, array_filter(CRUD::backOffsetLimit(false), function($param) { return $param !== ''; }));
      $params = [];
      foreach ($param2s as $param2) array_key_exists($param2[0], $params) || $params[$param2[0]] = $param2[1];
    }

    $href = parse_url($href);
    $href['query'] = http_build_query($params, '?');
    return self::unparseUrl($href);
  }

  public static function unparseUrl($parsedUrl) { 
    $arr = [];
    isset($parsedUrl['scheme']) && array_push($arr, $parsedUrl['scheme'] . '://');
    isset($parsedUrl['user']) && array_push($arr, $parsedUrl['user'] . (isset($parsedUrl['pass']) ? ':' . $parsedUrl['pass'] : '') . '@');
    isset($parsedUrl['host']) && array_push($arr, $parsedUrl['host']);
    isset($parsedUrl['port']) && array_push($arr, ':' . $parsedUrl['port']);
    isset($parsedUrl['path']) && array_push($arr, $parsedUrl['path']);
    isset($parsedUrl['query']) && array_push($arr, '?' . $parsedUrl['query']);
    isset($parsedUrl['fragment']) && array_push($arr, '#' . $parsedUrl['fragment']);
    
    return implode('', $arr); 
  } 

  public static function backOffsetLimit($merge = true) {
    $gets      = \Input::get();
    $limitKey  = \CRUD\Table\Pagination::$limitKey;
    $offsetKey = \CRUD\Table\Pagination::$offsetKey;
    
    $params = [];
    isset($gets[$limitKey])  && array_push($params, $limitKey . '=' . $gets[$limitKey]);
    isset($gets[$offsetKey]) && array_push($params, $offsetKey . '=' . $gets[$offsetKey]);

    return $merge ? $params ? '?' . implode('&', $params) : '' : $params;
  }
}

spl_autoload_register(function($className) {
  $namespace = getNamespaces($className);

  if (!($namespace && $namespace[0] === 'CRUD')) 
    return false;

  Load::lib(($namespace ? implode(DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR : '') . deNamespace($className));
  return class_exists($className);
});