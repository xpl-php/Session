<?php

namespace xpl\Session;

interface SessionInterface extends \ArrayAccess, \Countable {
	
	public function setDriver(Driver\SessionDriverInterface $driver);
	
	public function start();
	
	public function started();
	
	public function destroy();
	
	public function getId();
	
	public function getName();
	
	public function get($var);
	
	public function set($var, $value);
	
	public function has($var);
	
	public function remove($var);
	
}
