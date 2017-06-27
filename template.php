<?php

function _itsadmin_admin_links() {
  global $user;
  $links[] = '<a href="'. url('user') .'" class="user-name">'. $user->name .'</a>';
  $links[] = '<a href="'. url('logout') .'">Logout</a>';
  $links = implode(' | ', $links);

  return $links;
}

function itsadmin_body_class($left, $right) {
  if (((arg(0) == 'admin' AND (arg(2))) || $left != '') && ((arg(0) == 'node' AND arg(1) == 'add' AND arg(2)) OR (arg(0) == 'node' AND arg(2) == 'edit'))) {
    $class = 'sidebars';
  }
  else {
    if ($left != '' || (arg(0) == 'admin' AND (arg(2)))) {
      $class = 'sidebar-left';
    }
    if ((arg(0) == 'node' AND arg(1) == 'add' AND arg(2)) OR (arg(0) == 'node' AND arg(2) == 'edit')) {
      $class = 'sidebar-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

drupal_add_js(path_to_theme() . '/js/jquery.ui.datepicker.js');

function _itsadmin_admin_navigation() {
  $path = base_path() . path_to_theme();
  $base = path_to_theme();

  $menu_tree = array();
  global $user;

  if((end(array_keys($user->roles))==R_EXPORT_ROLE)){
	$menu_tree[] = array('url' => 'form/export', 'title' => t('Dashboard'), 'icon' => $path .'/images/oxygen/navigation/all.png');
	}else{
	$menu_tree[] = array('url' => 'admin', 'title' => t('Dashboard'), 'icon' => $path .'/images/oxygen/navigation/all.png');
	}

  $replace = array(
    'admin/content' => t('Content'),
    'admin/build' => t('Build'),
    'admin/settings' => t('Settings'),
    'admin/user' => t('Users')
  );

  $menu = menu_navigation_links('navigation', 1);
  foreach ($menu as $item) {
    if ($replace[$item['href']]) {
      $item['title'] = $replace[$item['href']];
    }
    $icon = $base .'/images/oxygen/navigation/'. str_replace("/","_",$item['href']) .'.png';
    if (!file_exists($icon)) {
      $icon = $path .'/images/oxygen/navigation/default.png';
    }
    else {
      $icon = $path .'/images/oxygen/navigation/'. str_replace("/","_",$item['href']) .'.png';
    }
    $menu_tree[] = array('url' => $item['href'], 'title' => t($item['title']), 'icon' => $icon);
  }
  $output = '<ul>';
  foreach ($menu_tree as $item) {

    $arg = explode('/', $item['url']);
    if (arg(0) == $arg[0] AND isset($arg[1]) && arg(1) == $arg[1]) {
      $id = ' id="current"';
    }
    else {
      $id = '';
    }
    $output .= '<li'. $id .'><a href="'. url($item['url']) .'"><img src="'. $item['icon'] .'" alt="'. $item['title'] .'" /><br />'. $item['title'] .'</a>';
    $output .= '</li>';
  }
  $output .= '</ul>';

  return $output;
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function itsadmin_preprocess_page(&$vars) {
  $vars['tabs2'] = menu_secondary_local_tasks();
  // Hook into color.module
  if (module_exists('color')) {
    _color_page_alter($vars);
  }
  $vars['ie_styles'] = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}
