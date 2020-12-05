<?php
class ValidateAnnotation extends Validator
{
    const MIN_LEN = 20;
    const MAX_LEN = 5000;
    const CODE_EMPTY = 'ERROR_ANNOTATION_EMPTY';
    const CODE_MIN_LEN = 'ERROR_ANNOTATION_MIN_LEN';
    const CODE_MAX_LEN = 'ERROR_ANNOTATION_MAX_LEN';
    const CODE_INVALID = 'ERROR_ANNOTATION_INVALID';

    protected function validate()
    {
        $data = $this->data;

        if (mb_strlen($data) == 0) {
            $this->setError(self::CODE_EMPTY);
        } else {
            if (mb_strlen($data) < self::MIN_LEN) {
                $this->setError(self::CODE_MIN_LEN);
            } elseif (mb_strlen($data) > self::MAX_LEN) {
                $this->setError(self::CODE_MAX_LEN);
            } elseif ($this->isContainQuotes($data)) {
                $this->setError(self::CODE_INVALID);
            }
        }
    }
}
