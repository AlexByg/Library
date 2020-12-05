<?php
class AbstractSelect
{
    private $db;
    private $from = '';
    private $where = '';
    private $order = '';
    private $limit = '';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function from($table_name, $fields)
    {
        $table_name = $this->db->getTableName($table_name);
        $from = '';

        if ($fields == '*') {
            $from = '*';
        } else {
            for ($i = 0; $i < count($fields); $i++) {
                if (($pos_1 = strpos($fields[$i], '(')) !== false) {
                    $pos_2 = strpos($fields[$i], ')');
                    $from .= substr($fields[$i], 0, $pos_1).'(`'.
                        substr($fields[$i], $pos_1 + 1, $pos_2 - $pos_1 - 1).
                        '`),';
                } else {
                    $from .= '`'.$fields[$i].'`,';
                }
            }

            $from = substr($from, 0, -1);
        }

        $from .= " FROM `$table_name`";
        $this->from = $from;

        return $this;
    }

    public function where($where, $values = [], $and = true)
    {
        if ($where) {
            $where = $this->db->getQuery($where, $values);
            $this->addWhere($where, $and);
        }

        return $this;
    }

    public function order($field, $asc = true)
    {
        if (is_array($field)) {
            $this->order = 'ORDER BY ';

            if (!is_array($asc)) {
                $temp = [];

                for ($i = 0; $i < count($field); $i++) {
                    $temp[] = $asc;
                }

                $asc = $temp;
            }

            for ($i = 0; $i < count($field); $i++) {
                $this->order .= '`'.$field[$i].'`';
                $this->order .= !$asc[$i] ? ' DESC,' : ',';
            }

            $this->order = substr($this->order, 0, -1);
        } else {
            $this->order = "ORDER BY `$field`";

            if (!$asc) {
                $this->order .= ' DESC';
            }
        }

        return $this;
    }

    public function limit($count, $offset = 0)
    {
        $count = (int) $count;
        $offset = (int) $offset;

        if ($count < 0 || $offset < 0) {
            return false;
        }

        $this->limit = "LIMIT $offset, $count";

        return $this;
    }

    public function __toString()
    {
        if ($this->from) {
            $ret = 'SELECT '.$this->from.' '.$this->where.' '.$this->order.
                ' '.$this->limit;
        } else {
            $ret = '';
        }

        return $ret;
    }

    private function addWhere($where, $and)
    {
        if ($this->where) {
            $this->where .= $and ? ' AND ' : ' OR ';
            $this->where .= $where;
        } else {
            $this->where = "WHERE $where";
        }
    }
}
