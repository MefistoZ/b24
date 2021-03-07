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

}