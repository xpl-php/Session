<?php

namespace Phpf\Session\Driver;

interface SessionDriverInterface extends \Countable {
	
	public function start();
	
	public function destroy();
	
	public function isStarted();
	
	public function getId();
	
	public function setId($id);
	
	public function getName();
	
	public function setName($name);
	
	public function get($var, $default = null);
	
	public function set($var, $val);
	
	public function exists($var);
	
	public function remove($var);
	
	public function getFromGroup($group, $key);
	
	public function addToGroup($group, $key, $value);
	
}
