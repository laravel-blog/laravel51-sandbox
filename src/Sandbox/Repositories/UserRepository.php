<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 29.07.15
 * Time: 17:36
 * Project: sandbox
 */

namespace Laravelblog\Sandbox\Repositories;
use Bosnadev\Repositories\Eloquent\Repository;
use Laravelblog\Sandbox\User;

class UserRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return config('sandbox.user_model');
    }

    /**
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable
     */
    public function me() {
        if(!auth()->check()) {
            return false;
        }
        return auth()->user();
    }

    public function isAdmin() {
        return ($oMe = static::me() and $oMe->is_admin == true);
    }
}