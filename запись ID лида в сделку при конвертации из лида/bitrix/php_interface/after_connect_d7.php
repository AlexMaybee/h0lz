<?php
/* Ansible managed: /etc/ansible/roles/web/templates/after_connect_d7.php.j2 modified on 2015-11-06 16:19:26 by root on s1.holz.com.ua */
$connection = \Bitrix\Main\Application::getConnection();

$connection->queryExecute("SET NAMES 'utf8'");
$connection->queryExecute("SET collation_connection = 'utf8_unicode_ci'");

