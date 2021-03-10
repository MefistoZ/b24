<?php

use Base\Handlers\MainHandler;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$eventManager = EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnAfterUserAuthorize', [MainHandler::class, 'afterUserAuthorize']);
$eventManager->addEventHandler('main', 'OnBeforeUserDelete', [MainHandler::class, 'beforeUserDelete']);
$eventManager->addEventHandler('main', 'OnBuildGlobalMenu', [MainHandler::class, 'buildGlobalMenu']);
$eventManager->addEventHandler('crm', 'OnAfterCrmLeadUpdate', [MainHandler::class, 'afterCrmLeadUpdate']);
$eventManager->addEventHandler('main', 'OnBeforeUserLogin', [MainHandler::class, 'beforeUserLogin']);

