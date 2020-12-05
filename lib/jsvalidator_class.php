<?php
class JSValidator
{
    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function title($t_empty = true, $t_type = false)
    {
        return $this->getBaseData($t_empty, $t_type, 'ValidateTitle', 'name');
    }

    public function textInput(
        $class,
        $t_empty = true,
        $t_type = true,
        $t_min_len = false,
        $type = ''
    ) {
        return $this->getBaseData($t_empty, $t_type, $class, $type, $t_min_len);
    }

    public function name($class = 'ValidateName', $t_empty = true, $t_type = true)
    {
        return $this->getBaseData($t_empty, $t_type, $class, 'name');
    }

    public function number($t_empty = true)
    {
        $cl = $this->getBase();
        $cl->type = 'number';

        if ($t_empty) {
            $cl->t_empty = $this->message->get(ValidateYear::CODE_EMPTY);
        }

        $cl->max_val = ValidateYear::MAX_VAL;
        $cl->min_val = ValidateYear::MIN_VAL;
        $cl->t_type = $this->message->get(ValidateYear::CODE_INVALID_VAL);

        return $cl;
    }

    public function image()
    {
        $cl = $this->getBase();
        $cl->t_empty = $this->message->get('ERROR_IMG_EMPTY');

        return $cl;
    }

    private function getBaseData(
        $t_empty,
        $t_type,
        $class,
        $type,
        $t_min_len = false
    ) {
        $cl = $this->getBase();
        $cl->type = $type;
        $cl->max_len = $class::MAX_LEN;
        $cl->t_max_len = $this->message->get($class::CODE_MAX_LEN);

        if ($t_empty) {
            $cl->t_empty = $this->message->get($class::CODE_EMPTY);
        }

        if ($t_type) {
            $cl->t_type = $this->message->get($class::CODE_INVALID);
        }

        if ($t_min_len) {
            $cl->min_len = $class::MIN_LEN;
            $cl->t_min_len = $this->message->get($class::CODE_MIN_LEN);
        }

        return $cl;
    }

    private function getBase()
    {
        $cl = new stdClass();
        $cl->type = '';
        $cl->min_len = '';
        $cl->max_len = '';
        $cl->t_min_len = '';
        $cl->t_max_len = '';
        $cl->t_empty = '';
        $cl->t_type = '';
        $cl->min_val = '';
        $cl->max_val = '';

        return $cl;
    }
}
