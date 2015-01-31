<?php

namespace xpl\Session {
	class functions {
		// dummy class
	}
}

namespace {
	
	use xpl\Session\Session;
	
	/**
	 * Sets a session variable.
	 */
	function session_set($var, $val) {
		return Session::instance()->set($var, $val);
	}
	
	/**
	 * Returns a session variable.
	 */
	function session_get($var) {
		return Session::instance()->get($var);
	}
	
	function flash_notice($text, $type = 'warning', $heading = null, $dismissable = true) {
		Session::instance()->addNotice(alert($text, $type, $heading, $dismissable));
	}
	
	function flash_notices_output($before = '', $after = '') {
		if (Session::instance()->hasNotices()) {
			return $before.Session::instance()->renderNotices().$after;
		}
	}

}
