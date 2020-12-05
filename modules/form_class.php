<?php
class Form extends ModuleHornav
{
    public function __construct()
    {
        parent::__construct();

        $this->add('name');
        $this->add('action');
        $this->add('method', 'post');
        $this->add('form_header');
        $this->add('message');
        $this->add('check', true);
        $this->add('enctype');
        $this->add('inputs', null, true);
        $this->add('jsv', null, true);
    }

    public function addJSV($field, $jsv)
    {
        $this->addObj('jsv', $field, $jsv);
    }

    public function text($name, $label = '', $value = '', $default_v = '')
    {
        $this->input($name, 'text', $label, $value, $default_v);
    }

    public function fileImage($name, $label = '')
    {
        $this->input($name, 'file', $label);
    }

    public function number(
        $name,
        $label = '',
        $max_v = '',
        $min_v = '',
        $value = false,
        $step = false
    ) {
        $cl = new stdClass();
        $cl->name = $name;
        $cl->type = 'number';
        $cl->label = $label;
        $cl->max_v = $max_v;
        $cl->min_v = $min_v;
        $cl->step = $step;
        $cl->value = $value;
        $this->inputs = $cl;
    }

    public function textarea($name, $label, $rows = 3, $value = '')
    {
        $cl = new stdClass();
        $cl->name = $name;
        $cl->type = 'textarea';
        $cl->label = $label;
        $cl->rows = $rows;
        $cl->value = $value;
        $this->inputs = $cl;
    }

    public function submit($value, $name = false)
    {
        $this->input($name, 'submit', '', $value);
    }

    private function input($name, $type, $label, $value = false, $default_v = false)
    {
        $cl = new stdClass();
        $cl->name = $name;
        $cl->type = $type;
        $cl->label = $label;
        $cl->value = $value;
        $cl->default_v = $default_v;
        $this->inputs = $cl;
    }

    public function getTmplFile()
    {
        return 'form';
    }
}
