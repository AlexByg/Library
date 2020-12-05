<?php
class ValidateAuthor extends Validator
{
    const MAX_LEN = 100;
    const CODE_EMPTY = 'ERROR_AUTHOR_EMPTY';
    const CODE_MAX_LEN = 'ERROR_AUTHOR_MAX_LEN';
    const CODE_INVALID = 'ERROR_AUTHOR_INVALID';

    protected function validate()
    {
        $data = $this->data;

        if (mb_strlen($data) == 0) {
            $this->setError(self::CODE_EMPTY);
        } elseif (mb_strlen($data) > self::MAX_LEN) {
            $this->setError(self::CODE_MAX_LEN);
        } else {
            if (mb_strlen($data) > self::MAX_LEN) {
                $this->setError(self::CODE_MAX_LEN);
            } elseif ($this->isContainQuotes($data)) {
                $this->setError(self::CODE_INVALID);
            }
        }
    }
}
