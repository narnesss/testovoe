<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Bitrix vars
 *
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var array $arItem
 * @var string $templateFolder
 * @var CBitrixComponentTemplate $this
 */
?>
<section class="feedback-section container">
    <form class="send_form" method="post" id="feedback">
        <div class="wrapper row">
            <div class="block-tittle col-12">Написать нам</div>
            <div class="d-flex justify-content-center col-12 col-xl-6">
                <div class="row">
                    <div class="feedback-input block col-12 col-md-6 col-xl-12 order-md-0">
                        <input class="require" type="text" name="NAME" placeholder="Ваше ФИО">
                        <input class="require phone_mask " data-mask="phone_mask" type="tel" name="PHONE"
                               placeholder="Ваш телефон">
                        <input class="require" type="email" name="EMAIL" placeholder="Ваш e-mail">
                        <input class="require" type="text" name="BIRTH" placeholder="Ваша дата рождения">
                        <input class="" type="text" name="ADRESS" placeholder="Ваш адрес">
                        <input class="require" type="text" name="RECEPTION" placeholder="Дата и время приема">
                        <select id="filial_filter" class="filials__select" name="FILIAL"
                                onchange="loadDepartments(this)">
                            <option value="del_filter">Выбрать филиал</option>
                            <?php foreach ($arResult['ITEMS']["FILIALS"] as $item): ?>
                                <option xml_id="<?= $item["XML_ID"] ?>" value="<?= $item["NAME"] ?>">
                                    <?= $item['NAME'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="department_filter" class="departments__select" name="DEPARTMENT"
                                disabled="disabled" onchange="loadDoctors(this)">
                            <option value="del_filter">Выбрать отделение</option>
                            <?php foreach ($arResult['ITEMS']["DEPARTMENTS"] as $item): ?>
                                <option xml_id="<?= $item["XML_ID"] ?>" value="<?= $item["NAME"] ?>">
                                    <?= $item['NAME'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <select id="doctor_filter" class="doctors__select" name="DOCTOR" disabled="disabled">
                            <option value="del_filter">Выбрать врача</option>
                            <?php foreach ($arResult['ITEMS']["DOCTORS"] as $item): ?>
                                <option xml_id="<?= $item["XML_ID"] ?>" value="<?= $item["NAME"] ?>">
                                    <?= $item['NAME'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="feedback-description block col-12 order-md-2">
                        <textarea name="TEXT" placeholder="Текст письма"></textarea>
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-8">
                                <label class="checkbox-container">Соглашаюсь с обработкой персональных данных
                                    <input type="checkbox" name="CHECKBOX" id="agree">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-12 col-md-8 col-xl-4 d-flex">
                                <button class="btn">отправить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>