<?php

namespace Ita\Traits;

trait ValidationTrait
{
    public function checkName($name)
    {
        if (preg_match("/^(([a-zA-Z'\" -]{2,50})|([а-яА-ЯЁёІіЇїҐґЄє'\" -]{2,50}))$/u", $name)) {
            return true;
        }
        return false;
    }

    public function checkCompany($company)
    {
        if (preg_match("/^(([a-zA-Z'\" -]{3,50})|([а-яА-ЯЁёІіЇїҐґЄє'\" -]{3,50}))$/u", $company)) {
            return true;
        }
        return false;
    }

    public function checkMail($mail)
    {
        if (check_email($mail)) {
            return true;
        }
        return false;
    }

    public function checkPhone($phone)
    {
        if (preg_match("/^((7)+([0-9]){10})$/", $phone)) {
            return true;
        }
        return false;
    }

    public function getClearPhone($phone)
    {
        return str_replace(array('+', ' ', '(', ')', '-'), '', $phone);
    }
}