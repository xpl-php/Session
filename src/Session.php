<?php

namespace Phpf\Session;

use Phpf\Session\Driver\SessionDriverInterface;

class Session implements \ArrayAccess, \Countable {
	
	protected $driver;
	
	/**
	 * Constructor
	 * @param iSessionDriver|null
	 */
	public function __construct(SessionDriverInterface $driver = null) {
		if (isset($driver)) {
			$this->setDriver($driver);
		}
	}
	
	/**
	 * Sets the session driver
	 * 
	 * @param iSessionDriver $driver
	 * @return $this
	 */
	public function setDriver(SessionDriverInterface $driver) {
		$this->driver = $driver;
		return $this;
	}
	
	/**
	 * Start the session.
	 * @return boolean
	 */
	public function start() {
		return $this->driver->start();
	}
	
	/**
	 * Whether session is started.
	 * @return boolean
	 */
	public function isStarted() {
		return $this->driver->isStarted();
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
	public function exists($var) {
		return $this->driver->exists($var);
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
	 * Adds an item to a named group (an array).
	 * 
	 * @param string $group Group name (an array if used with get(), etc.).
	 * @param string $key Item name (key in array).
	 * @param mixed $value Item value (array value).
	 * @return $this;
	 */
	public function addToGroup($group, $key, $value) {
		$this->driver->addToGroup($group, $key, $value);
		return $this;
	}
	
	/**
	 * Returns an item from a named group.
	 * 
	 * @param string $group Group name.
	 * @param string $key Item name.
	 * @return mixed Item value if set, otherwise null.
	 */
	public function getFromGroup($group, $key) {
		return $this->driver->getFromGroup($group, $key);
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
		$this->addToGroup('flash_notices', mt_rand(), $markup);
		return $this;
	}
	
	public function hasNotices() {
		return $this->exists('flash_notices');
	}
	
	/**
	 * Returns a concatenated HTML string of the flash notice(s).
	 * 
	 * @return string Notice HTML or empty string.
	 */
	public function renderNotices() {
		$s = '';
		if ($this->hasNotices()) {
			$s = implode("\n", $this->get('flash_notices'));
			$this->remove('flash_notices');
		}
		return $s;
	}
	
	/**
	 * @param $index 
	 * @param $newval 
	 * @return void
	 */
	public function offsetSet($index, $newval) {
		$this->driver->set($index, $newval);
	}

	/**
	 * @param $index 
	 * @return mixed
	 */
	public function offsetGet($index) {
		return $this->driver->get($index);
	}

	/**
	 * @param $index 
	 * @return void
	 */
	public function offsetUnset($index) {
		$this->driver->remove($index);
	}

	/**
	 * @param $index 
	 * @return boolean
	 */
	public function offsetExists($index) {
		return $this->driver->exists($index);
	}
	
	/**
	 * @return integer
	 */
	public function count() {
		return $this->driver->count();
	}
	
	public function __get($var) {
		return $this->get($var);
	}
	
	public function __set($var, $val) {
		$this->set($var, $val);
	}
	
	public function __isset($var) {
		return $this->exists($var);
	}
	
	public function __unset($var) {
		$this->remove($var);
	}
	
}
