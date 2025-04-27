<?php
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/vendor/autoload.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/vendor/autoload.php");
}

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandlerCompatible("iblock", "OnBeforeIBlockPropertyUpdate", "SendMail");
function SendMail(&$arParams)
{

}