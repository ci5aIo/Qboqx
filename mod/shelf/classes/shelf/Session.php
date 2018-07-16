<?php

namespace Shelf;

use ElggUser;

class Session {

	/**
	 * Gets the logged in user
	 * @return ElggUser
	 */
	public function getLoggedInUser() {
		return elgg_get_logged_in_user_entity();
	}

	/**
	 * Return the current logged in user by guid
	 * @return int
	 */
	public function getLoggedInUserGuid() {
		$user = $this->getLoggedInUser();
		return $user ? $user->guid : 0;
	}

	/**
	 * Returns whether or not the viewer is currently logged in and an admin user
	 * @return bool
	 */
	public function isAdminLoggedIn() {
		$user = $this->getLoggedInUser();
		return $user && $user->isAdmin();
	}

	/**
	 * Returns whether or not the user is currently logged in
	 * @return bool
	 */
	public function isLoggedIn() {
		return (bool) $this->getLoggedInUser();
	}

	/**
	 * Get current ignore access setting
	 * @return bool
	 */
	public function getIgnoreAccess() {
		return elgg_get_ignore_access();
	}

	/**
	 * Set ignore access.
	 *
	 * @param bool $ignore Ignore access
	 * @return bool Previous setting
	 */
	public function setIgnoreAccess($ignore = true) {
		return elgg_set_ignore_access($ignore);
	}

}
