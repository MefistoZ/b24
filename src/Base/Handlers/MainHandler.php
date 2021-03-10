<?php

namespace Base\Handlers;

use Base\User\UserTools;
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use COption;
use CTimeMan;
use CTimeManEntry;

class MainHandler
{

    const USER_MENU_ID = 'menu_users';
    const HIGHLOAD_LEAD_LOG_IBLOCK_ID = 2;

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

    public static function afterCrmLeadUpdate($fields)
    {
        if (!empty($fields)) {
            global $USER;
            Loader::includeModule("highloadblock");

            $leadLogHL = HighloadBlockTable::getById(self::HIGHLOAD_LEAD_LOG_IBLOCK_ID)->fetch();
            $entity = HighloadBlockTable::compileEntity($leadLogHL);
            $entityDataClass = $entity->getDataClass();

            $data = [
                "UF_LEAD_ID" => $fields['ID'],
                "UF_USER_ID" => (int)$USER->GetID(),
                "UF_UPDATE_LEAD_DATE" => date("d.m.Y H:i:s")
            ];
            $entityDataClass::add($data);
        }
    }

    public static function beforeUserLogin(&$arFields): bool
    {
        return UserTools::isWorkingTime($arFields);
    }
}