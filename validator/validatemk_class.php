<?php
class ValidateMK extends Validator
{
    const MAX_LEN = 255;
    const CODE_EMPTY = 'ERROR_MK_EMPTY';
    const CODE_MAX_LEN = 'ERROR_MK_MAX_LEN';
    const CODE_INVALID = 'ERROR_MK_INVALID';

    protected function validate()
    {
        $data = $this->data;

        if (mb_strlen($data) == 0) {
            $this->setError(self::CODE_EMPTY);
        } else {
            if (mb_strlen($data) > self::MAX_LEN) {
                $this->setError(self::CODE_MAX_LEN);
            } elseif ($this->isContainQuotes($data)) {
                $this->setError(self::CODE_INVALID);
            }
        }
    }
}
