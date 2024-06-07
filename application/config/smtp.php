<?php
defined('BASEPATH') or exit('No direct script access allowed');


/* SMTP ayarlarınızı yapın */
$config['protocol'] = 'smtp';
/* SMTP sunucu adresi */
$config['smtp_host'] = 'smtp.yandex.com.tr';
/* SMTP kimlik doğrulama kullan */
$config['smtp_auth'] = true;
/* SMTP portu */
$config['smtp_port'] = 465;
/* SMTP kullanıcı adı */
$config['smtp_user'] = 'almanusulu';
/* SMTP ismi */
$config['smtp_name'] = 'Egitim Sistemi';
/* SMTP şifre */
$config['smtp_pass'] = 'inwherbaloyafxdh';
/* Güvenli bağlantı türü - tls veya ssl kullanabilirsiniz */
$config['smtp_secure'] = 'ssl';
/* E-posta içeriği HTML olarak */
$config['mail_type'] = 'html';
/* Türkçe karakter sorunu yaşamamak için utf-8 kullanın */
$config['charset'] = 'utf-8';
/* Enter tuşuna basıldığında bir alt satıra geçilsin */
$config['newline'] = "\r\n";
/* Gönderici eposta */
$config['smtp_sender_from'] = 'almanusulu@yandex.ru';
/* Gönderici ismi */
$config['smtp_sender_name'] = 'Egitim Sistemi';
