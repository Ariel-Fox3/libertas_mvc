<?php
namespace Mubbi;

class Permissions {
  private $user_permissions = array();
  private $session = NULl;

  public function _get(array $permissionsUsuario = null) {
    $permissions = array();

    $ignore = array(
			'common/home',
			'account/login',
			'account/logout',
			'common/header',
      'common/navbar',
      'common/sidebar',
      'common/footer',
			'error/not_found',
			'error/permission'
		);

		$files = array();

		$path = array(CONTROLLER . '*');

		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next) as $file) {
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				if (is_file($file)) {
					$files[] = $file;
				}
			}
		}

		sort($files);
					
		foreach ($files as $file) {
			$controller = substr($file, strlen(CONTROLLER));

			$permission = substr($controller, 0, strrpos($controller, '.'));

			if (!in_array($permission, $ignore)) {
				$permissions[] = $permission;
			}
		}

    $nodes = array();

    if ($permissionsUsuario !== null) {
      $nodes_user = $permissionsUsuario;

      foreach ($permissions as $p) {
        $t = array('text' => $p, 'access' => false, 'modify' => false);
        if (isset($nodes_user) && isset($nodes_user[$p])) {
          $t['modify'] = isset($nodes_user[$p]['modify']) && $nodes_user[$p]['modify'] == true ? true : false;
          $t['access'] = isset($nodes_user[$p]['access']) && $nodes_user[$p]['access'] == true ? true : false;
        }

        $nodes[$p] = $t;
      }
    } else {
      foreach ($permissions as $p) {
        $nodes[$p] = array('text' => $p, 'access' => false, 'modify' => false);
      }
    }

    return $nodes;

  }

  public function getPermissions(array $permissionsUsuario = null) {   
    if ($permissionsUsuario === null && isset($this->user_permissions) && sizeof($this->user_permissions) > 0) {
      return $this->user_permissions;
    } else {
      return $this->_get($permissionsUsuario);
    }
  }

	public function __construct($registry) {
    $this->session = $registry->get('session');
    $this->user_permissions = $this->getPermissions();
	}


}