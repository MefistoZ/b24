<?php

namespace Base\User;

use CUser;

class UserTools
{

    /**
     * @param $userFields
     */
    public static function userToLog(array $userFields) : void
    {
        $logMassage = 'USER_ID: ' . $userFields['user_fields']['ID'] . "\n"
            . 'USER_NAME: ' . $userFields['user_fields']['NAME'];
        AddMessage2Log($logMassage, 'user', 0);
    }

}