<?php

/**
 * Автор: Константин Дейнека
 *
 * Дата реализации: 10.02.2022 14:12
 *
 * Дата изменения: 11.02.2022 20:45
 *
 * Утилита для работы с тестовыми классами (User и Users)
**/

require_once( "User.php" );

if (class_exists('User')) {
    require_once 'Users.php';
} else {
    echo "Класс User не найден";
}

$pers = new User(1,'Konstantin','Deineka','1985-03-06',1,'Mozyr');
print_r($pers);
