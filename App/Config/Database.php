<?php
namespace App\Config;

class Database {
    public function getConnection() {
        $host = "127.0.0.1";
        $user = "root";
        $password = "";
        $db = "bodybud";
        $port = 3308; 

        $mysqli = new \mysqli($host, $user, $password, $db, $port);

        if ($mysqli->connect_error) {
            die("Koneksi gagal: " . $mysqli->connect_error);
        }

        return $mysqli;
    }
}
