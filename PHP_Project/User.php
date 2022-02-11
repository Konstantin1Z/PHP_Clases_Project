<?php

/**
 * Автор: Константин Дейнека
 *
 * Дата реализации: 10.02.2022 14:12
 *
 * Дата изменения: 11.02.2022 10:05
 *
 * Утилита для работы с тестовыми классами (User и Users)
 *
 * Название класса: User
 * 
 * Класс предназначен для основных монипуляций с пользователем (создание, добавление, удаление и т.д)
 * Класс принимает следующие параметры (аргументы):
 * 1) Идентификатор (id) - уникальное целое число (больше ноля);
 * 2) Имя (name) - строка, содержащая буквы;
 * 3) Фамилия (surname) - строка, содержащая буквы;
 * 4) Дата рождения (birdthDay) - строка, формата даты (ГГГГ-ММ-ДД);
 * 5) Пол (sex) - бинарное целочисленное значение, либо 0, либо 1;
 * 6) Город рождения - строка, содержащая буквы;
 * 
 * Класс имеет следующие методы:
 * saveUser() - Записывает экземпляр класса в БД;
 * loadById(id) - ищет в БД запись о пользователе, чей id указан аргументом и присваевает его экземпляру класса;
 * deleteUser(id) - удаляет из БД запись о пользователе, чей id указан в аргументе метода;
 * updateUser(id,name,surname,birthDay,sex,city) - обновляет запись в БД, т.е. перезаписывает информацию о пользователе по его id;

 * Статические методы:
 * age(User) - возвращает целое число количества полных лет исходя из даты рождения экземпляра класса;
 * realSex(User) - Возвращает строку с наименованием пола пользователя исходя из бинарного значения аргумента экземпляра класса (0-Женщина, 1-Мужчина).

 * При указании аргументов экземпляра класса все входные величины проходят валидацию на соответствие типов и ожидаемых значений, иначе выводится соответствующее сообщение об ошибке.
 * 
 **/
class User
{
	
	public $id = 0;
	public $name = '';
	public $surname = '';
	public $birdthDay = '';
	public $sex = 0;
	public $city = '';

	function __construct($id, $name, $surname, $birthDay, $sex, $city)
	{

		$msg = '';

		if (gettype($id) != 'integer' || $id < 0) {
			$msg = 'Id должен быть числовым значением от ноля и выше.';
		}
		else
		$this->id = $id;

		if (gettype($name) != 'string' 
			|| !preg_match('/^[a-zA-Zа-яА-Я ]+$/', $name) 
			|| $name == '') {

			$msg = $msg . ' Имя должно состоять из букв и не может быть пустым.';	
		} else {
			$this->name = $name;
		}

		if (gettype($surname) != 'string' 
			|| !preg_match('/^[a-zA-Zа-яА-Я ]+$/', $surname) 
			|| $surname == '') {
			$msg = $msg . ' Фамилия должна состоять из букв и не может быть пустой строкой.';
	    } else {
	    	$this->surname=$surname;
	    }
		
		if (gettype($birthDay) != 'string' 
			|| !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $birthDay) 
			|| $birthDay == '') {
			$msg = $msg . ' Введите дату рождения корректного формата (ГГГ-ММ-ДД).';
	    } else {
	    	$this->birthDay = $birthDay;
	    }
		
		if (gettype($sex) != 'integer' || $sex < 0 || $sex > 1)
		$msg = $msg . ' Пол указывается числовым значением, 0 - женский, 1 - мужской';
		else
		$this->sex = $sex;

		if (gettype($city) != 'string' 
			|| !preg_match('/^[a-zA-Zа-яА-Я ]+$/', $city) 
			|| $city == '') {
			$msg = $msg . ' Имя должно состоять из букв и не может быть пустым.';
		} else {
			$this->city=$city;
		}
		
		if ($msg == '') {
			$msg = 'Все поля (аргументы) заполнены корректно. Экземпляр класса успешно создан!';
		}
		
		echo $msg;

	}

	public function saveUser()
	{

		$mysqli = new Mysqli($server, $user, $pas, $db); 
		$mysqli->query('SET CHARSET utf8');
		$query = $mysqli->query("INSERT INTO `users` VALUES('$this->id',
							    '$this->name', '$this->surname', '$this->birthDay', 
							    '$this->sex', '$this->city')");

	}

	public function loadById($id)
	{

	if (gettype($id) != 'integer' || $id < 0) {
		echo 'Введён не корректный ID (Id должен быть числовым значением от ноля и выше).';
	} else {

		$mysqli = new Mysqli($server, $user, $pas, $db); 
		$mysqli->query('SET CHARSET utf8');
		$query = $mysqli->query("SELECT 'id', 'name', 'surname', 'birthDay', 
								'sex', 'city' FROM `users` WHERE 'id' = ' . $id . '");

		if (mysqli_num_rows($query) != 0) {

			$userVal = $query->fetch_assoc();

			$this->id = $userVal['id'];
			$this->name = $userVal['name'];
			$this->surname = $userVal['surname'];
			$this->birthDay = $userVal['birthDay'];
			$this->sex = $userVal['sex'];
			$this->city = $userVal['city'];

		} else {
			echo 'Пользователя с таким ID нет в базе';
		} 
		}
	}

	public function deleteUser($id)
	{
		if (gettype($id) != 'integer' || $id < 0) {
			echo 'Введён не корректный ID (Id должен быть числовым значением от ноля и выше).';
		} else {
			$mysqli = new Mysqli($server, $user, $pas, $db);
			$query = $mysqli->query("SELECT 'id', 'name', 'surname', 'birthDay', 
								'sex', 'city' FROM `users` WHERE 'id' = ' . $id . '");
			if (mysqli_num_rows($query) != 0) {
				$queryDel = $mysqli->query("DELETE FROM `users` WHERE 'id' = ' . $id . '");
			} else {
				echo 'Пользователя с таким ID нет в базе';
			} 

		}
	}

	public function updateUser($id, $name, $surname, $birthDay, $sex, $city)
	{
		$mysqli = new Mysqli($server, $user, $pas, $db); 
		$mysqli->query('SET CHARSET utf8');
		$query = $mysqli->query("UPDATE `users` SET 'name' = ' . $this->name . ', 
								'surname' = ' . $this->surname . ', 'birthDay' = ' . $this->birthDay . ', 
								'sex' = ' . $this->sex . ', 'city' = ' . $this->city . ' 
								WHERE 'id' = ' . $this->id . '");
	}

	public static function age($User)
	{
		$birthday = $User->birthDay;
		$birthday_timestamp = strtotime($birthday);
		$fullYears = date('Y') - date('Y', $birthday_timestamp);
		if (date('md', $birthday_timestamp) > date('md')) {
			$fullYears--;
		}
		return $fullYears;
	}

	public static function realSex($User)
	{

		$sexBinare = $User->sex;
		$gender = '';
		if ($sexBinare == 1) {
			$gender='Мужчина';
		}
		if ($sexBinare == 0) {
			$gender='Женщина';
		}

		return $gender;

	}

}
