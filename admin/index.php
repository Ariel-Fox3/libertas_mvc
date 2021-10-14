<?php

function _require_all($dir, $depth=0) {
	$scan = glob("$dir/*");
	foreach ($scan as $path) {
		if (preg_match('/\.php$/', $path)) {
			require_once $path;
		}
		elseif (is_dir($path)) {
			_require_all($path, $depth+1);
		}
	}
}

_require_all(dirname(__DIR__) . '/framework/system/engine');
_require_all(dirname(__DIR__) . '/framework/system/library');


use Mubbi\Session;
use Mubbi\Config;
use Mubbi\Database;
use Mubbi\Loader;
use Mubbi\Response;
use Mubbi\Document;
use Mubbi\Url;
use Mubbi\Request;
use Mubbi\Registry;
use Mubbi\Func;
use Mubbi\Language;
use User\User;
/**
 * Mubbi Custom PHP Framework
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018, Mubbasher Ahmed Qureshi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package mubbi/php-framework-MVC-psr
 * @author  Mubbasher Ahmed Qureshi
 * @copyright   Copyright (c) 2018, Mubbasher Ahmed. (http://mubbiqureshi.com/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @since   Version 1.0.0
 */

// Composer AutoLoader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Define Configs
include 'app/configs/directories.php';
include CONFIGS . 'app.php';
include CONFIGS . 'configs.php';

// Get Controller
if (!isset($_GET['route'])) {
	$_GET['route'] = 'common/home';
}

// SET Controller & Action
$params = explode('/', $_GET['route']);
$controller = $params[0];
$file = $params[1];
$function = 'index';
if (isset($params[2])) {
	$function = $params[2];
}

// Verify Controller
if (!file_exists(CONTROLLER . $controller . '/' . $file . '.php')) {
    die('Controller: ' . $controller . '/' . $file . ' file does not exist');
}

// Include Controller
require_once CONTROLLER . $controller . '/' . $file . '.php';
$controllerClass = 'Mubbi\\Controller'.ucfirst($controller) . ucfirst($file) . '';

// Verify Controller Class
if (!class_exists($controllerClass)) {
	die('Class: ' . $controllerClass . '  does not exist');
}

$useModel = true;
// Verify Model
if (!file_exists(MODELS . $controller . '/' . $file . '.php')) {
	// die('Model: ' . $controller . '/' . $file . ' file does not exist');
	$useModel = false;
}


require(dirname(__DIR__) . '/' . FRAMEWORK . 'startup.php');

?>
