<?php
$chatId = ''; // peer_id чата (беседы), пример - 2000000001
$linkComplaints = htmlentities(file_get_contents("link.txt", 'r')); // ссылка на файл с "жалобами", пример - http://gusakov.myarena.ru/web.log

// Подключение VK API
require_once 'vk.php';
$vk = new VK();

$count = htmlentities(file_get_contents("config.txt", 'r')); // Индекс последней введенной строки

if($count != 0)
{
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
    }
}

// Ответы на сообщения в вк
if($vk->data['type'] == 'message_new')
{
    $peer_id = $vk->data['object']['peer_id'];                   // peer id
    $text = mb_strtolower($vk->data['object']['text'], 'utf-8'); // Перевод текста в нижний регистр

    if((strpos($text, '/link ') !== false) && ($peer_id == 212386903 || $peer_id == 31608272))
    {
        $link = explode("/link ", $text);
        $fd = fopen("link.txt", 'w+');
        fwrite($fd, $link[1]);
        fclose($fd);

        $vk->send($peer_id, 'Ссылка на файл успешно обновлена'); // Вывод сообщения в чат
    }

    if($text == '/index' && ($peer_id == 212386903 || $peer_id == 31608272))
    {
        $fd = fopen("config.txt", 'w+');
        fwrite($fd, count($complaints) - 1);
        fclose($fd);

        $vk->send($peer_id, 'Индекс успешно обновлен'); // Вывод сообщения в чат
    }

    if($text == '/peer_id' && ($peer_id == 212386903 || $peer_id == 31608272))
    {
        $vk->send($peer_id, $peer_id); // Вывод сообщения в чат
    }
}

