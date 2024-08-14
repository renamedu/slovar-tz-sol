<?php

abstract class DatabaseHandler {
    protected $connection;

    public function __construct() {
        $this->connect();
    }

    abstract protected function connect();

    public function addData($data) {
        if (!$this->connection) {
            $this->logError("Отсутствует подключение к базе данных.");
            return false;
        }

        return $this->insertData($data);
    }

    protected function logError($message) {
        $logMessage = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
        file_put_contents('mysql.log', $logMessage, FILE_APPEND);
    }

    abstract protected function insertData($data);
}

class MySQLDatabaseHandler extends DatabaseHandler {
    protected function connect() {
        $this->connection = @mysqli_connect('localhost', 'username', 'password', 'database');

        if (!$this->connection) {
            $this->logError("Ошибка подключения к базе данных: " . mysqli_connect_error());
        }
    }

    protected function insertData($data) {
        $query = "INSERT INTO table_name (column_name) VALUES ('" . mysqli_real_escape_string($this->connection, $data) . "')";

        if (mysqli_query($this->connection, $query)) {
            return true;
        } else {
            $this->logError("Ошибка вставки данных: " . mysqli_error($this->connection));
            return false;
        }
    }
}

$databaseHandler = new MySQLDatabaseHandler();
$data = "lfyyst";

if ($databaseHandler->addData($data)) {
    echo "Данные успешно добавлены в базу данных.";
} else {
    echo "Ошибка при добавлении данных в базу данных.";
}
