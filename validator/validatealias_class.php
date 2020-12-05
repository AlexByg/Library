<?php
class ValidateAlias extends Validator
{
    const MAX_LEN = 100;

    protected function validate()
    {
        $data = $this->data;

        if (mb_strlen($data) == 0) {
            $this->setError(self::CODE_UNKNOWN);
        } else {
            if (mb_strlen($data) > self::MAX_LEN) {
                $this->setError(self::CODE_UNKNOWN);
            } elseif ($this->isContainQuotes($data)) {
                $this->setError(self::CODE_UNKNOWN);
            }
        }
    }
}
