<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send("毛泽东比他好周永康风骚小阿姨\n");
$client->send("比他好\n");
echo $client->recv();
$client->close();

?>
