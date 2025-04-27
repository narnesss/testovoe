<?php
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/log.txt");

use Bitrix\Main\Loader,
    Bitrix\Main\Application,
    Bitrix\Main\Engine\ActionFilter,
    Bitrix\Main\Engine\Contract\Controllerable,
    Bitrix\Main\Engine\ActionFilter\Authentication,
    Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock')) {
    ShowError(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    return;
}

class Feedback extends CBitrixComponent implements Controllerable
{
    use Ita\Traits\ValidationTrait;

    protected $request;
    protected $config;
    protected $data;
    private $mail;
    private $phone;
    private $name;
    private $adress;
    private $deparment;
    private $doctor;
    private $filial;
    private $reception;
    private $birth;
    private $text;
    private $newsletter;
    private $iblock_id;
    private $newsletterId;
    private $arEventFields;

    public function __construct($component = null)
    {

        parent::__construct($component);
        \Bitrix\Main\Loader::includeModule('iblock');

        $context = Application::getInstance()->getContext();
        $this->request = $context->getRequest();

        $this->mail = trim(htmlspecialchars($this->request->get('EMAIL')));
        $this->phone = trim(htmlspecialchars($this->request->get('PHONE')));
        $this->name = trim(htmlspecialchars($this->request->get('NAME')));
        $this->adress = trim(htmlspecialchars($this->request->get('ADRESS')));
        $this->deparment = trim(htmlspecialchars($this->request->get('DEPARTMENT')));
        $this->doctor = trim(htmlspecialchars($this->request->get('DOCTOR')));
        $this->filial = trim(htmlspecialchars($this->request->get('FILIAL')));
        $this->reception = trim(htmlspecialchars($this->request->get('RECEPTION')));
        $this->birth = trim(htmlspecialchars($this->request->get('BIRTH')));
        $this->text = trim(htmlspecialchars($this->request->get('TEXT')));
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    public function configureActions()
    {
        $filters = [
            new ActionFilter\HttpMethod(
                array(ActionFilter\HttpMethod::METHOD_POST)
            ),
            new ActionFilter\Csrf(),
        ];
        return [
            'add' => [
                '-prefilters' => [
                    Authentication::class
                ],
                '+prefilters' => $filters,
            ],
            'department' => [
                '-prefilters' => [
                    Authentication::class
                ],
                '+prefilters' => $filters,
            ],
            'doctor' => [
                '-prefilters' => [
                    Authentication::class
                ],
                '+prefilters' => $filters,
            ],
        ];
    }

    public function addAction()
    {

        if (!$this->checkName($this->name)) {
            throw new Exception(GetMessage('NAME_ERROR'), 400);
        }
        if (!$this->checkPhone($this->getClearPhone($this->phone))) {
            throw new Exception(GetMessage('PHONE_ERROR'), 400);
        }
        if (!$this->checkMail($this->mail)) {
            throw new Exception(GetMessage('EMAIL_ERROR'), 400);
        }
        if ($this->request->get('CHECKBOX') !== 'on') {
            throw new Exception(GetMessage('CHECKBOX_ERROR'), 400);
        }

        $arEventFields = array(
            'NAME' => $this->name,
            'PHONE' => $this->phone,
            'ADRESS' => $this->adress,
            'DEPARTMENT' => $this->deparment,
            'DOCTOR' => $this->doctor,
            'FILIAL' => $this->filial,
            'RECEPTION' => $this->reception,
            'BIRTH' => $this->birth,
            'EMAIL' => $this->mail,
            'TEXT' => $this->text,
        );
        $newArrayValue = [
            'IBLOCK_ID' => \Ita\Helpers::getIblockId('applications'),
            'ACTIVE' => 'N',
            'NAME' => $this->name . ' записался на ' . $this->reception,
            'IBLOCK_SECTION_ID' => false,
            'PROPERTY_VALUES' => $arEventFields
        ];

        $element = new CIBlockElement;
        $elementId = $element->add($newArrayValue);
        if ($element->LAST_ERROR <> '') {
            throw new Exception(GetMessage('ADD_NEW_EL_ERROR'), 422);
        }

        $user = $this->getUserByMail();
        if (!$user) {
            $this->addUser($elementId);
        } else {
            $this->updateUser($user, $elementId);
        }

        $mailFields = array(
            'AUTHOR' => $this->name,
            'PHONE' => $this->phone,
            'ADRESS' => $this->adress,
            'DEPARTMENT' => $this->deparment,
            'DOCTOR' => $this->doctor,
            'FILIAL' => $this->filial,
            'RECEPTION' => $this->reception,
            'BIRTH' => $this->birth,
            'AUTHOR_EMAIL' => $this->mail,
            'TEXT' => $this->text,
        );

        CEvent::Send(
            "FEEDBACK_FORM",
            SITE_ID,
            $mailFields
        );

        return array(
            "success" => GetMessage('SUCCESS'),
        );

    }

    public function departmentAction()
    {
        if (!empty($this->request->get('action')) && $this->request->get('action') == 'getDepartment') {
            $department = $this->getDepartment($this->request->get('filial'));
            if (isset($department)) {
                return json_encode($department); // возвращаем данные в JSON формате;
            }
        }
        return json_encode(array('Выберите филиал'));
    }

    public function doctorAction()
    {
        if (!empty($this->request->get('action')) && $this->request->get('action') == 'getDoctor') {
            $doctor = $this->getDoctor($this->request->get('xml_id'));
            if (isset($doctor)) {
                return json_encode($doctor);
            }
        }
        return json_encode(array('Выберите отделение'));
    }

    private function getDepartment($department)
    {
        $this->iblock_id = \Ita\Helpers::getIblockId('filial');
        $res = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => $this->iblock_id, "NAME" => $department),
            false,
            false,
            array("ID", "IBLOCK_ID", "PROPERTY_DEPARTMENT"));
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetProperties();
            $arIds = $arFields["DEPARTMENT"]["VALUE"];
        }

        $rsElements = CIBlockElement::GetList(
            array(),
            array(
                'ID' => $arIds,
                'CHECK_PERMISSIONS' => 'N'
            ),
            false,
            false,
            array('ID', 'NAME')
        );

        $arResult = [];
        while ($arElement = $rsElements->Fetch()) {
            $arResult[$arElement['ID']] = $arElement['NAME'];
        }

        return $arResult;
    }

    private function getDoctor($xml_id)
    {
        $this->iblock_id = \Ita\Helpers::getIblockId('doctors');
        $res = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => $this->iblock_id, "PROPERTY_DEPARTMENT.ID" => $xml_id),
            false,
            false,
            array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*"));
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arResult[] = $arFields["NAME"];
        }

        return $arResult;
    }

    private function getUserByMail()
    {
        $this->iblock_id = \Ita\Helpers::getIblockId('patients');
        $res = CIBlockElement::GetList(
            array(),
            array("IBLOCK_ID" => $this->iblock_id, "=PROPERTY_EMAIL" => $this->mail),
            false,
            false,
            array("ID", "IBLOCK_ID"));
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $user = $arFields["ID"];
        }
        return $user;
    }

    private function updateUser($user, $elementId)
    {
        $currentValues = CIBlockElement::GetByID($user)->GetNextElement();
        $currentProperty = $currentValues->GetProperties()['RECORDS']['VALUE'];
        if ($currentProperty) {
            $mergedValues = array_merge($currentProperty, array((string)$elementId));
        } else {
            $mergedValues = $elementId;
        }
        \CIBlockElement::SetPropertyValuesEx($user, \Ita\Helpers::getIblockId('patients'), ["RECORDS" => $mergedValues]);
    }

    private function addUser($elementId)
    {
        $arEventFields = array(
            'PHONE' => $this->phone,
            'ADRESS' => $this->adress,
            'BIRTH' => $this->birth,
            'EMAIL' => $this->mail,
            'RECORDS' => $elementId
        );
        $newArrayValue = [
            'IBLOCK_ID' => \Ita\Helpers::getIblockId('patients'),
            'ACTIVE' => 'N',
            'NAME' => $this->name,
            'IBLOCK_SECTION_ID' => false,
            'PROPERTY_VALUES' => $arEventFields
        ];
        $element = new CIBlockElement;
        $element->add($newArrayValue);
        if ($element->LAST_ERROR <> '') {
            throw new Exception(GetMessage('ADD_NEW_EL_ERROR'), 422);
        }

        $mailFields = array(
            'AUTHOR' => $this->name,
            'PHONE' => $this->phone,
            'ADRESS' => $this->adress,
            'DEPARTMENT' => $this->deparment,
            'DOCTOR' => $this->doctor,
            'FILIAL' => $this->filial,
            'RECEPTION' => $this->reception,
            'BIRTH' => $this->birth,
            'AUTHOR_EMAIL' => $this->mail,
            'TEXT' => $this->text,
        );

        CEvent::Send(
            "FEEDBACK_FORM_NEW",
            SITE_ID,
            $mailFields
        );
    }
}
