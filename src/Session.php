<?php

namespace xpl\Session;

use xpl\Common\Singleton;

class Session implements SessionInterface 
{
	
	use Singleton;
	
	protected $driver;
	
	/**
	 * Sets the session driver
	 * 
	 * @param SessionDriverInterface $driver
	 * @return $this
	 */
	public function setDriver(Driver\SessionDriverInterface $driver) {
		$this->driver = $driver;
		return $this;
	}
	
	/**
	 * Start the session.
	 * @return boolean
	 */
	public function start(array $cookie_params = array()) {
		
		if (! isset($this->driver)) {
			$this->setDriver(new Driver\Native($cookie_params));
		}
		
		return $this->driver->start();
	}
	
	/**
	 * Whether session is started.
	 * @return boolean
	 */
	public function started() {
		return $this->driver->started();
	}
	
	/**
	 * Destroy the session.
	 * @return boolean
	 */
	public function destroy() {
		return $this->driver->destroy();
	}
	
	/**
	 * Get the session ID.
	 * @return string
	 */
	public function getId() {
		return $this->driver->getId();
	}
	
	/**
	 * Set the session ID.
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->driver->setId($id);
		return $this;
	}
	
	/**
	 * Get the session name.
	 * @return string
	 */
	public function getName() {
		return $this->driver->getName();
	}
	
	/**
	 * Set the session name.
	 * @param string $name
	 * @return string
	 */
	public function setName($name) {
		$this->driver->setName($name);
		return $this;
	}
	
	/**
	 * Get a session variable.
	 * @param string $var
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($var, $default = null) {
		return $this->driver->get($var, $default);
	}
	
	/**
	 * Set a session variable.
	 * @param string $var
	 * @param mixed $val
	 * @return $this
	 */
	public function set($var, $val) {
		$this->driver->set($var, $val);
		return $this;
	}
	
	/**
	 * Whether a session variable exists.
	 * @param string $var
	 * @return boolean
	 */
	public function has($var) {
		return $this->driver->has($var);
	}
	
	public function exists($var) {
		return $this->has($var);
	}
	
	/**
	 * Remove/delete a session variable.
	 * @param string $var
	 * @return $this
	 */
	public function remove($var) {
		$this->driver->remove($var);
		return $this;
	}
	
	/**
	 * Adds an HTML flash notice to be displayed on next request.
	 * 
	 * When used in conjuction with renderNotices(), the items will
	 * be removed when rendered.
	 * 
	 * @param string $markup HTML markup to display on the next request.
	 * @return $this
	 */
	public function addNotice($markup) {
		$this->set('flash_notices.'.uniqid(), $markup);
		return $this;
	}
	
	/**
	 * Returns a concatenated HTML string of the flash notice(s).
	 * 
	 * @return string Notice HTML or empty string.
	 */
	public function renderNotices() {
		$str = '';
		
		if ($this->has('flash_notices')) {
				
			$str = implode("\r\n", $this->get('flash_notices'));
			
			$this->remove('flash_notices');
		}
		
		return $str;
	}
	
	public function hasNotices() {
		return $this->has('flash_notices');
	}
	
	/**
	 * @param $index 
	 * @param $newval 
	 * @return void
	 */
	public function offsetSet($index, $newval) {
		$this->set($index, $newval);
	}

	/**
	 * @param $index 
	 * @return mixed
	 */
	public function offsetGet($index) {
		return $this->get($index);
	}

	/**
	 * @param $index 
	 * @return void
	 */
	public function offsetUnset($index) {
		$this->remove($index);
	}

	/**
	 * @param $index 
	 * @return boolean
	 */
	public function offsetExists($index) {
		return $this->has($index);
	}
	
	public function __get($var) {
		return $this->get($var);
	}
	
	public function __set($var, $val) {
		$this->set($var, $val);
	}
	
	public function __isset($var) {
		return $this->has($var);
	}
	
	public function __unset($var) {
		$this->remove($var);
	}
	
	/**
	 * @return integer
	 */
	public function count() {
		return $this->driver->count();
	}
	
}
