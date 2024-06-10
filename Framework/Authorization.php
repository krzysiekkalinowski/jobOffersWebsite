<?php

namespace Framework;

use Framework\Session;

class Authorization
{
    /**
     * Check if user is owner of resource
     * 
     * @param int resourceID
     * @return bool
     */
    public static function isOwner($resourceID)
    {
        $sessionUser = Session::get('user');
        if ($sessionUser !== null && isset($sessionUser['id'])) {
            $sessionUserId = (int) $sessionUser['id'];
            return $sessionUserId === $resourceID;
        }

        return false;
    }
}
