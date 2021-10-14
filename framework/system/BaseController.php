<?php

namespace Mubbi;

_require_all(dirname(__DIR__) . '/system/engine');
_require_all(dirname(__DIR__) . '/system/library');

/**
* Base Controller
*/
/*
class BaseController {
	
	public $registry = NULL;
	public $load = NULL;
	public $document = NULL;
	public $session = NULL;
	public $url = NULL;
	public $db = NULL;
	public $user = NULL;
	public $config = NULL;
	public $request = NULL;
	public $response = NULL;

	public function __construct() {
		$this->db = new Database(DB_TYPE, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);
		$data = array(
			'assets' => HTTP_SERVER . ASSETS
		);
		$this->request = new Request();
		$this->load = new Loader($data, $this);
		$this->document = new Document();
		$this->url = new Url();
		$this->response = new Response($this);
	}

	// public function setOutput($html) {
	// 	$this->document->setContent($html);
	// 	$this->document->setUrl($_GET);
	// 	$this->url->link($_GET['route']);
	// 	$this->document->render();
	// }

	public function get($key) {
		return $this->$key;
	}

	public function set($key, $val) {
		$this->$key = $val;
	}

}
*/

/**
 * Controller class
 *
 * @property Document document
 * @property Loader load
 * @property Request request
 * @property Session session
 * @property Response response
 * @property Url url
 * @property Config config
 * @property Func func
 * @property Language language
 * @property Painel painel
 * @property Permissions painel
 */

abstract class BaseController {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

}
