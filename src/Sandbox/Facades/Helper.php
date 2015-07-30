<?php
/**
 * User: Stefan Riedel <sr@laravel-blog.de>
 * Date: 30.07.15
 * Time: 11:49
 * Project: sandbox
 */

namespace Laravelblog\Sandbox\Facades;


use Illuminate\Support\Facades\Facade;

class Helper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'helper';
    }
}