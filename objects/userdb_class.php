<?php
class UserDB extends ObjectDB
{
    protected static $table = 'users';
    private $new_password = null;

    public function __construct()
    {
        parent::__construct(self::$table);

        $this->add('login', 'ValidateLogin');
        $this->add('password', 'ValidatePassword');
        $this->add('is_admin', 'ValidateBoolean', null, 0);
    }

    public function loadOnLogin($login)
    {
        return $this->loadOnField('login', $login);
    }

    public function login()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION['auth_login'] = $this->login;
        $_SESSION['auth_password'] = $this->password;
    }

    public static function logout()
    {
        if (!session_id()) {
            session_start();
        }

        unset($_SESSION['auth_login']);
        unset($_SESSION['auth_password']);
    }

    public static function authUser($login = false, $password = false)
    {
        if ($login) {
            $auth = true;
        } else {
            if (!session_id()) {
                session_start();
            }

            if (!empty($_SESSION['auth_login'])
                && !empty($_SESSION['auth_password'])
            ) {
                $login = $_SESSION['auth_login'];
                $password = $_SESSION['auth_password'];
            } else {
                return;
            }

            $auth = false;
        }

        $user = new UserDB();

        if ($auth) {
            $password = self::hash($password, Config::SECRET);
        }

        $select = new Select();
        $select->from(self::$table, ['COUNT(id)'])
            ->where('`login` = '.self::$db->getSQ(), [$login])
            ->where('`password` = '.self::$db->getSQ(), [$password]);
        $count = self::$db->selectCell($select);

        if ($count) {
            $user->loadOnLogin($login);

            if ($auth) {
                $user->login();
            }

            return $user;
        }

        if ($auth) {
            throw new Exception('ERROR_AUTH_USER');
        }
    }
}
