<?php

namespace Ita;

use Bitrix\Highloadblock as HL;

class Helpers
{

    public static function isArray($arr)
    {
        return isset($arr) && is_array($arr) && !empty($arr);
    }

    public static function isString($str)
    {
        return isset($str) && is_string($str) && !empty($str);
    }

    public static function getIblockId(string $code, string $type = '')
    {
        if (empty($code)) {
            return null;
        }
        if (\CModule::IncludeModule('iblock')) {
            $resc = \CIBlock::GetList(array(), array('=CODE' => $code, 'TYPE' => $type), false);
            if ($arrc = $resc->Fetch()) {
                return $arrc['ID'];
            } else {
                return null;
            }
        } else {
            echo "<pre>";
            print_r('IBLOCK module not load');
            echo "<pre>";
            return null;
        }
    }

    public static function getHighloadEntity(string $name)
    {
        $hldata = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => array('=NAME' => $name)
        ])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hldata);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    public static function getHighloadId(string $code)
    {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList([
            'filter' => ['=NAME' => $code]
        ])->fetch();
        if (!$hlblock) {
            throw new \Exception('[04072017.1331.1]');
        }
        return $hlblock;
    }

    public static function addKeyToArray(array &$arr, string $key)
    {
        $result = [];
        foreach ($arr as $item) {
            $result[$item[$key]] = $item;
        }
        $arr = $result;
    }

    public static function dump($content)
    {
        echo "<pre>";
        print_r($content);
        echo "</pre>";
    }

    /**
     * Сортируем многомерный массив по значению вложенного массива
     * @param $array array многомерный массив который сортируем
     * @param $field string название поля вложенного массива по которому необходимо отсортировать
     * @return array отсортированный многомерный массив
     */
    function customMultiSort($array, $field)
    {
        $sortArr = array();
        foreach ($array as $key => $val) {
            $sortArr[$key] = $val[$field];
        }

        array_multisort($sortArr, $array);

        return $array;
    }

    public static function check_string_array($haystack, $needle)
    {
        foreach ($haystack as $word) {
            if (gettype($needle) == 'string') {
                if (stripos($word, $needle) !== false) {
                    return 'true'; // if found
                }
            } else {
                foreach ($needle as $needle_item) {
                    if (stripos($word, $needle_item) !== false) {
                        return 'true'; // if found
                    }
                }
            }
        }
        return 'false'; // if not found
    }

    public static function getXmlIdByCode(string $code)
    {
        if (empty($code)) {
            return null;
        }
        if (\CModule::IncludeModule('iblock')) {

            $resc = \CIBlockElement::GetList(array(), array('=CODE' => $code), false);
            if ($arrc = $resc->Fetch()) {
                return $arrc['XML_ID'];
            } else {
                return null;
            }
        } else {
            ShowError("Ошибка!");
            return null;
        }
    }
}
