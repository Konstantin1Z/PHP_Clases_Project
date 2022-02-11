<?php

/**
 * Название класса: Users
 * 
 * Класс предназначен для основных монипуляций с группой пользователей (поиск, удалкение).
 * Класс принимает всего один аргумент - массив с идентификаторами пользователей.
 * Класс имеет следующие методы:
 * findUsers(id) - ищет пользователей в БД по id указанным в аргументе-массиве и добавляет в массив. Возвращает массив с экземплярами класса User;
 * delUsers(id) - Удаляет пользователей из БД, чьи id были указаны в аргументе-массиве и удаляет их методом класса User (deleteUser(id)).
 * 
 * Данный класс объявляется и работает только в случае подключения (существования) класса User.
 */

require_once('User.php');

if (!class_exists('User')) {
   echo 'Класс User не найден. Класс Users не будет объявлен!'; 
} else {

    class Users
    {
	
        public $id = 0;
        public $name = '';
        public $surname = '';
        public $birdthDay = '';
        public $sex = 0;
        public $city = '';

        function __construct($idArray)
        {
            $this->idArray = $idArray;
        }

        public function findUsers($idArray)
        {

            $masResult = array();

            if(gettype($idArray) == 'array' && count($idArray) > 0){

                $mysqli = new Mysqli($server, $user, $pas, $db);
                $mysqli->query( 'SET CHARSET utf8' );

                for($i = 0; $i < count($idArray); $i++) {

                    $query = $mysqli->query('SELECT * FROM `users` WHERE id = "' . $idArray[$i] . '"');

                    $userVal = $query->fetch_assoc();

                    $user = new User($userVal['id'], $userVal['name'], $userVal['surname'], 
                                     $userVal['birdthDay'], $userVal['sex'], $userVal['city']);

                    array_push($masResult, $user);

                }

                return $masResult;

            } else {
                return 'Вы передали либо пустой массив, либо вовсе не массив!';
            }

        }

        public function delUsers($idArray)
        {

            if (gettype($idArray) == 'array' && count($idArray) > 0) {

                for($i = 0; $i < count($idArray); $i++) {

                    if (gettype($idArray[$i]) == 'integer' && $idArray[$i] > 0) {

                        $user = new User;
                        $user->deleteUser($idArray[$i]);
                    }

                }

            } else {
                echo 'Вы передали либо пустой массив, либо вовсе не массив!';
            }
    
        }

    }

}
