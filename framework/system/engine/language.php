<?php

namespace Mubbi;

class Language {
	private $default = 'pt-br';
	private $directory;
	private $data = array();

	/**
	 * Constructor
	 *
	 * @param	string	$file
	 *
	 */
	public function __construct($directory = '') {
		$this->directory = $directory;
	}

	/**
	 *
	 *
	 * @param	string	$key
	 *
	 * @return	string
	 */
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : $key);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	/**
	 *
	 *
	 * @return	array
	 */
	public function all() {
		return $this->data;
	}

	/**
	 *
	 *
	 * @param	string	$filename
	 * @param	string	$key
	 *
	 * @return	array|string
	 */
	public function load($filename, $key = '') {
		$_ = array();

		$file = LANGUAGE . $this->default . '/' . $filename . '.php';

		if (is_file($file)) {
			require($file);
		}

		$file = LANGUAGE . $this->directory . '/' . $filename . '.php';
		
		if (is_file($file)) {
			require($file);
		}

		if ($key === '') {
			$this->data = array_merge($this->data, $_);
		} else {
			$this->data = $_[$key];
		}

		return $this->data;
	}
}
