<?php
namespace WPLoginCounter;

/*
Plugin Name: Wordpress Login Counter
Plugin URI: https://github.com/iniq/WP-Login-Counter
Description: Add columns to the Users list indicating number of times logged in and when the last login occurred.
Version: 1.0
Author: Derek Hall
Author URI: http://iniquitous.ca
*/

/*
Copyright 2012  Derek Hall (dhall@iniquitous.ca)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
*/

// Define constants to be used within this namespace
const LOGIN_TIME = 'last_login';
const LOGIN_COUNT = 'login_count';
const SINGLE = true;

/**
 * Return an integer representing how many times the user with the given ID has logged in
 * @param int $userID: The ID of a WP_User
 * @return int: How many times the WP_User has logged in
 */
function getLoginCount($userID) {
	// A blank string is returned if the key does not exist, which will eval to zero anyway
	return intval(get_user_meta($userID, LOGIN_COUNT, SINGLE));
}

// On each User login, record the current time and increment their login count
function recordLogin($username, $userObject) {

	$userID = $userObject->ID;

	// Update the last login time
	update_user_meta($userID, LOGIN_TIME, current_time('mysql'));

	// Update the login count. Requires knowing what it was before
	$loginCount = getLoginCount($userID);
	update_user_meta($userID, LOGIN_COUNT, ++$loginCount);
}
add_action('wp_login', 'WPLoginCounter\\recordLogin', 10, 2);
