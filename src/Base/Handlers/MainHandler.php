<?php

namespace Base\Handlers;

use Base\User\UserTools;

class MainHandler
{

    /**
     * @param $userFields
     */
    public static function afterUserAuthorize(array $userFields) : void
    {
        UserTools::userToLog($userFields);
    }

    /**
     * @param int $userId
     */
    public static function beforeUserDelete(int $userId): bool
    {
        return UserTools::preventUserDeletion($userId);
    }

}