<?php

$chatId = ''; // peer_id чата (беседы), пример - 2000000001
$linkComplaints = ''; // ссылка на файл с "жалобами", пример - http://fastdl.myarena.ru/14-10278/maps/chat.txt

// Подключение VK API
require_once 'vk.php';
$vk = new VK();

$count = htmlentities(file_get_contents("config.txt", 'r')); // Индекс последней введенной строки
$complaints = explode("\n", file_get_contents($linkComplaints)); // Получаем содержимое файла и делим на строки

// Если есть новые строки, то выводим их в чат
if($count <= count($complaints))
{
    if($complaints[$count] != '')
    {
        $vk->send($chatId, $complaints[$count]); // Вывод сообщения в чат

        // Обновляем информацию о строках
        $fd = fopen("config.txt", 'w+');
        fwrite($fd, $count + 1);
        fclose($fd);
    }
    else
    {
        exit;
    }
}

