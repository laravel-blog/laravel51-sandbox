<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 29.07.15
 * Time: 17:57
 * Project: sandbox
 */

function allowed($sAction = null,$sRole = null) {
    return \Helper::allowed($sAction, $sRole);
}

/**
 * to check user is a guest
 *
 * @return bool
 */
function is_guest() {
    return !\Auth::check();
}

function has_route($name) {
    return \Route::has($name);
}

/**
 * Generate a relative URL to a named route.
 *
 * @param  string  $name
 * @param  array   $params
 * @param  \Illuminate\Routing\Route  $route
 * @return string
 */
function relative_route($name, array $params = [], $route = null) {
    return route($name, $params, false, $route);
}