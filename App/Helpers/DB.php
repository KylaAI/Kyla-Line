<?php
namespace App\Helpers;
use App\Helpers\Hub\Singleton;

/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @version 0.0.1
 * @license MIT
 */
 use Config\Config;
class DB
{

    use Singleton;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $conf = Config::get('database');
        $this->pdo = new \PDO("mysql:host=".$conf['host'].";dbname=".$conf['dbname'], $conf['user'], $conf['pass'], [3=>2]);
    }

    /**
     * Call static pdo.
     *
     * @param  string $method
     * @param  array  $param
     * @return mixed
     */
    public static function __callStatic($method, $param)
    {
        return self::getInstance()->pdo->{$method}(...$param);
    }
}


/**
 * Error query handler.
 */
function pc($exe, \PDOStatement $pdo)
{
    if (! $exe) {
        var_dump($pdo->errorInfo());
        exit(1);
    }
    return true;
}
