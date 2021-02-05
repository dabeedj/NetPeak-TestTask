<?php

/**
 * Db Class
 * Database connection Singleton component
 */
class Db
{

    /**
     * Connect to DataBase
     * @return \PDO <p>Return PDO object</p>
     */

    public $pdo; // handle of the db connection
    private static $instance; //db connection instance

    private function __construct()
    {
        // Get connection params
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include($paramsPath);

        // Set up connection
        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $this->pdo = new PDO($dsn, $params['user'], $params['password']);
        
        // Set encoding and collation
        $this->pdo->exec('SET NAMES utf8 COLLATE utf8_general_ci');
    }

    /**
     * Try to connect or return existent connection
     * @return object
     */
    public static function getInstance() : object
    {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    /**
     * SQL Error parse and backtrace
     * @param array $mysql_error <p>MySQL result arraym, may contain errors</p>
     * @return void
     */
    public static function sql_error_parse(array $mysql_error) : void
    {
        if (isset($mysql_error[1])) {

            // Get debug backtrace info
            $bt = debug_backtrace();
            $caller = array_shift($bt);

            if (!is_array($mysql_error)) $mysql_error = [];

            $mysql_error['backtrace'] = $caller['file'] . ':' . $caller['line'];

            // Die with parsed error
            die (json_encode(['parsed_sql_error' => implode(', ', $mysql_error)]));
        }
    }

    /**
     * Commit previous transaction
     * @return void
     */
    public static function commitLastTransaction() : void
    {
        Db::getInstance()->pdo->commit();
    }

    /**
     * Rollback previous transaction
     * @return void
     */
    public static function rollbackLastTransaction() : void
    {
        Db::getInstance()->pdo->rollback();
    }

}
