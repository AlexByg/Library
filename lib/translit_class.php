<?php
class Translit
{
    public static function translitStr($str = '')
    {
        $str = (string) $str;
        $str = strip_tags($str);
        $str = str_replace(["\n", "\r"], ' ', $str);
        $str = preg_replace("/\s+/", ' ', $str);
        $str = trim($str);
        $str = mb_strtolower($str);
        $str = strtr(
            $str, [
                    'а' => 'a',
                    'б' => 'b',
                    'в' => 'v',
                    'г' => 'g',
                    'д' => 'd',
                    'е' => 'e',
                    'ё' => 'e',
                    'ж' => 'j',
                    'з' => 'z',
                    'и' => 'i',
                    'й' => 'y',
                    'к' => 'k',
                    'л' => 'l',
                    'м' => 'm',
                    'н' => 'n',
                    'о' => 'o',
                    'п' => 'p',
                    'р' => 'r',
                    'с' => 's',
                    'т' => 't',
                    'у' => 'u',
                    'ф' => 'f',
                    'х' => 'h',
                    'ц' => 'c',
                    'ч' => 'ch',
                    'ш' => 'sh',
                    'щ' => 'shch',
                    'ы' => 'y',
                    'э' => 'e',
                    'ю' => 'yu',
                    'я' => 'ya',
                    'ъ' => '',
                    'ь' => ''
                    ]
        );
        $str = preg_replace("/[^0-9a-z-_ ]/i", '', $str);
        $str = str_replace(' ', '-', $str);

        return $str;
    }
}
