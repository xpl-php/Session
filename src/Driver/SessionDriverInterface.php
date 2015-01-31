<?php

namespace xpl\Session\Driver;

interface SessionDriverInterface extends \Countable {
	
	public function start();
	
	public function started();
	
	public function destroy();
	
	public function getId();
	
	public function setId($id);
	
	public function getName();
	
	public function setName($name);
	
	public function get($var, $default = null);
	
	public function set($var, $val);
	
	public function has($var);
	
	public function remove($var);
	
}
