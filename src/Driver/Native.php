<?php

namespace xpl\Session\Driver;

use xpl\Utility\Arr;
use xpl\System\Server;

class Native implements SessionDriverInterface {
	
	/**
	 * If given array of cookie parameters, sets cookie parameters.
	 */
	public function __construct(array $cookie_params = array()) {
		
		ini_set('session.use_only_cookies', 1);
		
		$params = array_replace(array(
			'lifetime' => 86400*7,
			'path' => '/',
			'domain' => null,
			'secure' => false,
			'httponly' => false,
		), $cookie_params);
		
		if (empty($params['domain'])) {
			$params['domain'] = '.' . Server::getDomainName(Server::DOMAIN|Server::TLD);
		}
		
		session_set_cookie_params(
			$params['lifetime'], 
			$params['path'], 
			$params['domain'], 
			$params['secure'], 
			$params['httponly']
		);
	}
	
	/**
	 * Starts the session
	 * @return boolean Result of session_start()
	 */
	public function start() {
		return session_start();
	}
	
	/**
	 * Writes and closes the session.
	 */
	public function close() {
		session_write_close();
		return $this;
	}
	
	/**
	 * Destroys the current session.
	 */
	public function destroy() {
		
		$_SESSION = array();

		$this->unsetCookie();

		session_unset();

		if (session_id()) {
			session_destroy();
		}
	}
	
	/**
	 * Whether session is started.
	 * 
	 * @return boolean True if session started, otherwise false.
	 */
	public function started() {
		return '' !== session_id();
	}
	
	/**
	 * Returns session ID.
	 * 
	 * @return string Result of session_id()
	 */
	public function getId() {
		return session_id();
	}
	
	/**
	 * Sets session ID. Must be called before start().
	 * 
	 * @param string $id ID to use for current session.
	 * @return string Session ID
	 * @throws RuntimeException if session has been started.
	 */
	public function setId($id) {
		
		if ($this->isStarted()) {
			throw new \RuntimeException("Cannot set ID once session has been started.");
		}
		
		return session_id($id);
	}
	
	/**
	 * Returns session name.
	 * 
	 * @return string Session name
	 */
	public function getName() {
		return session_name();
	}
	
	/**
	 * Sets session name.
	 * 
	 * @param string $name Name to set as session name.
	 * @return string Name of current session.
	 */
	public function setName($name) {
		return session_name($name);
	}
	
	/**
	 * Returns session cookie if set.
	 * 
	 * @return string Cookie if set, otherwise null.
	 */
	public function getCookie() {
		$name = $this->getName();
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}
	
	/**
	 * Returns a session variable.
	 * 
	 * @param string $var Name of session variable.
	 * @param mixed $default Value to return if session variable does not exist.
	 */
	public function get($var, $default = null) {
		return Arr::get($_SESSION, $var) ?: $default;
	}
	
	/**
	 * Sets a session variable.
	 * 
	 * @param string $var Name of session variable.
	 * @param mixed $val Value of session variable.
	 * @return $this
	 */
	public function set($var, $val) {
		Arr::set($_SESSION, $var, $val);
		return $this;
	}
	
	/**
	 * Returns true if a session variable exists, otherwise false.
	 * 
	 * @param string $var Name of session variable.
	 * @return boolean Whether session variable exists.
	 */
	public function has($var) {
		return Arr::exists($_SESSION, $var);
	}
	
	/**
	 * Unsets a session variable.
	 * 
	 * @param string $var Name of session variable to unset.
	 * @return $this
	 */
	public function remove($var) {
		Arr::remove($_SESSION, $var);
		return $this;
	}
	
	/**
	 * @return integer
	 */
	public function count() {
		return count($_SESSION);
	}
	
	/**
	 * Unsets/invalidates session cookie.
	 * 
	 * @return $this
	 */
	protected function unsetCookie() {
		
		unset($_COOKIE[session_name()]);
		
		$p = session_get_cookie_params();
		
		setcookie(session_name(), '', time() - 31536000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
		
		return $this;
	}
	
}
