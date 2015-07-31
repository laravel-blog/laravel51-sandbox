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