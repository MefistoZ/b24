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

    /**
     * @param int $userId
     * @return bool
     */
    public static function preventUserDeletion(int $userId): bool
    {
        /**
         * todo: Заменить GetByID на GetList = увеличение производительности за счет добавление полей для выборки
         */
        $queryUser = CUser::GetByID($userId);
        $userFields = $queryUser->Fetch();
        if ($userFields['UF_USER_PROTECTED']) {
            global $APPLICATION;
            $APPLICATION->throwException('Нельзя удалять защищённого от удаления пользователя пользователя');
            return false;
        }
        return true;
    }

}