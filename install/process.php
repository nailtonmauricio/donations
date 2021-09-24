<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
    /*var_dump(
        $data
    );*/

    //criar validações para os campos

    //criar o banco de dados
    try {
        $conn = new PDO("mysql:host={$data["host_name"]}; charset={$data["charset"]}", $data["user_name"], $data["password"]);
        // set the PDO error mode to exception
        $conn ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE DATABASE {$data["db_name"]}";
        // use exec() because no results are returned
        $conn ->exec($sql);
        //echo "Database created successfully<br>";

        if(!is_file(__DIR__."/config.txt")){
            $statment = array("{$data["host_name"]}", "{$data["db_name"]}", "{$data["charset"]}", "{$data["user_name"]}", "{$data["password"]}");
            $config_file = implode(";", $statment);

            $file = "config.txt";
            $handle = fopen($file, 'a+');
            fwrite($handle, $config_file);
            fclose($handle);
        }

    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
    try{
        require_once "../config/config.php";

        $sqlFrequency = "CREATE TABLE `frequency` (`id` int(11) NOT NULL AUTO_INCREMENT,`name` varchar(60) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn -> exec($sqlFrequency);
        $insFrequency = "INSERT INTO `frequency` (`id`, `name`) VALUES (1, 'Único'), (2, 'Anual'), (3, 'Semestral'), (4, 'Bimestral'),(5, 'Mensal')";
        $conn -> exec($insFrequency);

        $sqlPayments = "CREATE TABLE `payments` (`id` int(11) NOT NULL AUTO_INCREMENT, `type` varchar(60) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn -> exec($sqlPayments);
        $insPayments = "INSERT INTO `payments` (`id`, `type`) VALUES (1, 'Crédito'), (2, 'Débito'), (3, 'Pix')";
        $conn -> exec($insPayments);

        $sqlCustomes = "CREATE TABLE `customers` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(220) NOT NULL, `birth_date` date NOT NULL, `document` varchar(14) DEFAULT NULL, `phone` varchar(15) DEFAULT NULL, `email` varchar(60) DEFAULT NULL, `address` varchar(200) DEFAULT NULL,`frequency_id` int(11) NOT NULL, `payment_id` int(11) NOT NULL, `contribution` float NOT NULL, `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(), `updated_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`), FOREIGN KEY (`frequency_id`) REFERENCES  frequency(id), FOREIGN KEY (`payment_id`) REFERENCES payments(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $conn -> exec($sqlCustomes);

        header("Location: ../index.php");

    } catch (PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }
    $conn = null;
}