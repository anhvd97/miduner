<?php

namespace App\Http\Middlewares;

use App\Main\Http\Middleware;
use App\Main\Http\Exceptions\AppException;

class Auth extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle($callback, $action, $params)
    {
        return parent::next($callback, $action, $params);
        foreach (getallheaders() as $key => $header) {
            if ($key == 'Authorization') {
                return parent::next($callback, $action, $params);
            }
        }
        throw new AppException("Unauthorized !", 401);
    }
}
