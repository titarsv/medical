<?php

class Database {

    function __construct() {

        $this->link = new mysqli('localhost', 'parser', 'parser', 'parser') ;

        if (!$this->link) {
            echo "Ошибка: Невозможно установить соединение с MySQL." . PHP_EOL;
            echo "Код ошибки errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Текст ошибки error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }

    }

    function add_category($parent, $href){
        $sql = "INSERT INTO `categories`(`parent`, `href`, `parsed`) VALUES ('".$parent."', '".$href."', 0)";
        mysqli_query($this->link, $sql);
    }

    function get_next_cat(){
        $result = mysqli_query ($this->link, "SELECT * FROM `categories` WHERE parsed = 0 LIMIT 0, 1");
        $cat = mysqli_fetch_assoc($result);
        return $cat['href'];
    }

    function update_cat($addr){
        $sql = "UPDATE `categories` SET `parsed`=1 WHERE `href` = '$addr'";
        mysqli_query($this->link, $sql);
    }

}