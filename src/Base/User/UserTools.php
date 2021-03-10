<?php

namespace Base\User;

use Bitrix\Main\Loader;
use COption;
use CTimeMan;
use CUser;

class UserTools
{

    const ADMIN_ID = 1;

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

    /**
     * @param $arFields
     * @return bool
     * @throws \Bitrix\Main\LoaderException
     */
    public static function isWorkingTime ($arFields): bool
    {
        if ($arFields['LOGIN'] === 'admin') {
            return true;
        }
        global $APPLICATION;
        Loader::includeModule('timeman');
        $workdayStart = CTimeMan::FormatTime(COption::GetOptionInt('timeman','workday_start', 32400), true);
        $workdayFinish = CTimeMan::FormatTime(COption::GetOptionInt('timeman','workday_finish', 64800), true);
        $currentTime = date('H:m');

        $currentDateTime = strtotime(date('Y-m-d')  ." ". $currentTime);
        $startDateTime = strtotime(date('Y-m-d')  ." ". $workdayStart);

        if (strtotime($workdayStart) <= strtotime($workdayFinish)) {
            $endDateTime = strtotime(date('Y-m-d')  ." ". $workdayFinish);
            $previousDayEnd = strtotime(date('Y-m-d')  ." ". $workdayFinish . "-1 days");
        } else {
            $endDateTime = strtotime(date('Y-m-d')  ." ". $workdayFinish . "+1 days");
            $previousDayEnd = strtotime(date('Y-m-d')  ." ". $workdayFinish );
        }

        if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
            return true;
        }
        if ($currentDateTime < $startDateTime && $currentDateTime < $previousDayEnd) {
            return true;
        } else {
            $APPLICATION->throwException("Сейчас нерабочее время");
            return false;
        }
    }

}