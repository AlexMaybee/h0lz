<?php
/* Ansible managed: /etc/ansible/roles/web/templates/after_connect.php.j2 modified on 2015-11-06 16:19:26 by root on s1.holz.com.ua */
$DB->Query("SET NAMES 'utf8'");
$DB->Query('SET collation_connection = "utf8_unicode_ci"');
