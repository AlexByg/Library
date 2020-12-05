<?php
class ValidateIMG extends Validator
{
    protected function validate()
    {
        $data = $this->data;
        $reg = "/^[a-z0-9-_]+\.(jpg|jpeg|png|gif)$/i";

        if (!is_null($data) && !preg_match($reg, $data)) {
            $this->setError(self::CODE_UNKNOWN);
        }
    }
}
