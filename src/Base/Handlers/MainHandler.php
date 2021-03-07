<?php

namespace Base\Handlers;

use Base\User\UserTools;

class MainHandler
{

    const USER_MENU_ID = 'menu_users';

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

    /**
     * @param $adminMenu
     * @param $moduleMenu
     */
    public static function buildGlobalMenu(&$adminMenu, &$moduleMenu): void
    {
        foreach ($moduleMenu as $key => $itemMenu) {
            if ($itemMenu['items_id'] === self::USER_MENU_ID) {
                $moduleMenu[$key]['items'][] = [
                    'text' => 'Новый пункт меню',
                    'title' => 'Новый пункт меню',
                    'url' => '/',
                ];
            }
        }
    }

}