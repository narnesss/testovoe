<?php

namespace Sprint\Migration;


class create_mail_event20250428014104 extends Version
{
    protected $author = "narnes";

    protected $description = "";

    protected $moduleVersion = "4.12.6";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('FEEDBACK_FORM_NEW', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Отправка сообщения через форму обратной связи',
  'DESCRIPTION' => '#AUTHOR# - Автор сообщения
#AUTHOR_EMAIL# - Email автора сообщения
#PHONE# - телефон автора сообщения
#ADRESS# - адрес автора сообщения
#DEPARTMENT# - отделение автора сообщения
#DOCTOR# - врач автора сообщения
#FILIAL# - филиал автора сообщения
#RECEPTION# - дата записи автора сообщения
#BIRTH# - дата рождения автора сообщения
#TEXT# - Текст сообщения
#EMAIL_FROM# - Email отправителя письма
#EMAIL_TO# - Email получателя письма',
  'SORT' => '150',
));
            $helper->Event()->saveEventType('FEEDBACK_FORM_NEW', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Sending a message using a feedback form',
  'DESCRIPTION' => '#AUTHOR# - Message author
#AUTHOR_EMAIL# - Author\'s e-mail address
#PHONE# - телефон автора сообщения
#ADRESS# - адрес автора сообщения
#DEPARTMENT# - отделение автора сообщения
#DOCTOR# - врач автора сообщения
#FILIAL# - филиал автора сообщения
#RECEPTION# - дата записи автора сообщения
#BIRTH# - дата рождения автора сообщения
#TEXT# - Message text
#EMAIL_FROM# - Sender\'s e-mail address
#EMAIL_TO# - Recipient\'s e-mail address',
  'SORT' => '150',
));
            $helper->Event()->saveEventMessage('FEEDBACK_FORM_NEW', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => 'admin@admin.ru',
  'SUBJECT' => '#SITE_NAME#: Сообщение из формы обратной связи',
  'MESSAGE' => 'Информационное сообщение сайта #SITE_NAME#
------------------------------------------

Новый пациент был добавлен на сайте

Автор: #AUTHOR#
E-mail автора: #AUTHOR_EMAIL#
#PHONE# - телефон автора сообщения
#ADRESS# - адрес автора сообщения
#DEPARTMENT# - отделение автора сообщения
#DOCTOR# - врач автора сообщения
#FILIAL# - филиал автора сообщения
#RECEPTION# - дата записи автора сообщения
#BIRTH# - дата рождения автора сообщения

Текст сообщения:
#TEXT#

Сообщение сгенерировано автоматически.',
  'BODY_TYPE' => 'text',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => 'ru',
  'EVENT_TYPE' => '[ FEEDBACK_FORM_NEW ] Отправка сообщения через форму обратной связи',
));
        }
}
