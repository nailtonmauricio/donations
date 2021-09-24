<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    var_dump(
        $data
    );

    //criar validações para os campos

    //criar o banco de dados
    try {
        $conn = new PDO("mysql:host={$data["host_name"]}; charset={$data["charset"]}", $data["user_name"], $data["password"]);

        // set the PDO error mode to exception
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE {$data["db_name"]}";
        // use exec() because no results are returned
        $conn ->exec($sql);
        echo "Database created successfully<br>";

        //criar arquivo config.txt
        $config = ["host_name" =>"{$data["host_name"]}", "db_name" => "{$data["db_name"]}", "user_name" => "{$data["user_name"]}", "password" =>"{$data["password"]}"];
        $directory = __DIR__."../config/";
        $fileName = $directory . "config.txt";
        $handle = fopen($fileName, 'a+');
        fwrite($handle, $config);
        fclose($handle);

    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}