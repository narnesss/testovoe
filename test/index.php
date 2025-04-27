<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->IncludeComponent(
    "bitrix:feedback",
    "appointment",
    array(),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");