<?php

namespace CRUD;

class Layout {
  public static function menus($currentUrl, $config) {
    $config = array_filter(array_map(function($group) use ($currentUrl) {

      $group['items'] = array_filter($group['items'], function($item) {
        if (!$router = \Router::findByName($item['router'])) return null;
        if (!$content = fileRead(PATH_APP_CONTROLLER . $router->path() . $router->className() . '.php')) return null;
        if (!(preg_match_all('/parent::__construct\s*\((?P<params>.*)\)/', $content, $r) && $r['params'])) return $item;
        eval('$content=[' . $r['params'][0] . '];');
        if (is_array($content) && ($content = arrayFlatten($content)) && !\M\Admin::current()->inRoles($content)) return null;
        return $item;
      });

      $group['items'] = array_map(function($item) use ($currentUrl) {
        $item['router'] = \Url::router($item['router']);
        $item['active'] = $item['router'] == $currentUrl;
        $item['class'] = implode(' ', array_filter(['menu-item', $item['icon'], $item['active'] ? 'active' : null], function($t) { return $t !== null; }));
        $item['data-cnt'] = isset($item['datas']['cnt']) ? $item['datas']['cnt'] : null;
        $item['data-cntlabel'] = isset($item['datas']['label']) ? $item['datas']['label'] : null;
        return $item;
      }, $group['items']);

      if (!$group['items'])
        return null;

      $group['active'] = array_filter(array_column($group['items'], 'active')) ? true : false;
      $group['class'] = implode(' ', array_filter(['menu-title', $group['icon'], $group['active'] ? 'active' : null], function($t) { return $t !== null; }));
      $group['data-cnt'] = array_sum(array_filter(array_map(function($group) { return isset($group['datas']['cnt']) && is_numeric($group['datas']['cnt']) ? $group['datas']['cnt'] + 0 : null; }, $group['items']), function($group) { return $group !== null; }));
      $group['data-cntlabel'] = implode(' ', array_filter(array_map(function($group) { return isset($group['datas']['label']) && $group['datas']['label'] !== '' ? $group['datas']['label'] : null; }, $group['items']), function($group) { return $group !== null; }));

      return $group;
    }, $config));

    $groups = array_map(function($group) {

      $title = \HTML\Span::create($group['text'])->attrs([
        'class' => $group['class'],
        'data-cnt' => $group['data-cnt'],
        'data-cntlabel' => $group['data-cntlabel']]);
      
      $items = array_map(function($item) {
        return \HTML\A::create($item['text'])->attrs([
          'class' => $item['class'],
          'href' => $item['router'],
          'data-cnt' => $item['data-cnt'],
          'data-cntlabel' => $item['data-cntlabel']
        ]);
      }, $group['items']);

      return $title . '<div class="menu-items n' . count($group['items']) . '">' . implode('', $items) . '</div>';
    }, $config);

    return '<div id="menu-container">' . implode('', $groups) . '</div>';
  }
}