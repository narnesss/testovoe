<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arResult
 * @var string $templateFolder
 */
\CJSCore::Init();
$this->addExternalJs(SITE_TEMPLATE_PATH . '/libs/imask-6.4.3/imask.js');
$this->addExternalJS(SITE_TEMPLATE_PATH . '/libs/fancybox4.0.31/fancy.js');
$this->addExternalCss(SITE_TEMPLATE_PATH . '/libs/fancybox4.0.31/fancy.css');


$res = CIBlockElement::GetList(
    array(),
    array("IBLOCK_CODE" => "doctors", "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
    false,
    false,
    array("XML_ID", "NAME", "PROPERTY_*"));
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields['PROPERTIES'] = $ob->GetProperties();
    $doctors[] = $arFields;
}
$arItem['DOCTORS'] = [];
foreach ($doctors as $doctor) {
    $arItem['DOCTORS'][] = $doctor;
}
$arItem['DOCTORS'] = array_unique($arItem['DOCTORS'], SORT_REGULAR);

$res = CIBlockElement::GetList(
    array(),
    array("IBLOCK_CODE" => "filial", "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
    false,
    false,
    array("XML_ID", "NAME", "PROPERTY_*"));
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields['PROPERTIES'] = $ob->GetProperties();
    $filials[] = $arFields;
}
$arItem['FILIALS'] = [];
foreach ($filials as $filial) {
    $arItem['FILIALS'][] = $filial;
}
$arItem['FILIALS'] = array_unique($arItem['FILIALS'], SORT_REGULAR);

$res = CIBlockElement::GetList(
    array(),
    array("IBLOCK_CODE" => "department", "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"),
    false,
    array(),
    array("XML_ID", "NAME", "PROPERTY_*"));
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arFields['PROPERTIES'] = $ob->GetProperties();
    $departments[] = $arFields;
}
$arItem['DEPARTMENTS'] = [];
foreach ($departments as $department) {
    $arItem['DEPARTMENTS'][] = $department;
}
$arItem['DEPARTMENTS'] = array_unique($arItem['DEPARTMENTS'], SORT_REGULAR);

$this->__component->arResult['ITEMS'] = $arItem;
unset($arItem);