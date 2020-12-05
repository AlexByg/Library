<?php
class ValidateYear extends Validator
{
    const MIN_VAL = 0;
    const MAX_VAL = 2020;
    const CODE_EMPTY = 'ERROR_YEAR_EMPTY';
    const CODE_INVALID_VAL = 'ERROR_YEAR_INVALID_VAL';

    protected function validate()
    {
        $data = $this->data;

        if (mb_strlen($data) == 0) {
            $this->setError(self::CODE_EMPTY);
        } elseif (filter_var(
            $data,
            FILTER_VALIDATE_INT,
            ['options' => [
                'min_range' => self::MIN_VAL,
                'max_range'=> self::MAX_VAL
                ]
            ]
        ) !== false
        ) {
            return true;
        } else {
            $this->setError(self::CODE_INVALID_VAL);
        }
    }
}
