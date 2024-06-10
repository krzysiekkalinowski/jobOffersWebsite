<?php

namespace Framework\Middleware;

use Framework\Session;

class Authorize
{
    /**
     * Check if user is authorized
     * 
     * @return bool
     */
    public function isAuthorized()
    {
        return Session::has('user') ? true : false;
    }

    /**
     * Hanlde users request
     * 
     * @param string $role
     * @return bool
     */
    public function handle($role)
    {
        if ($role === 'guest' && $this->isAuthorized()) {
            return redirect('/');
        } elseif ($role === 'auth' && !$this->isAuthorized()) {
            return redirect('/auth/login');
        }
    }
}
