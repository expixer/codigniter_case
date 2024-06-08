<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Smtp
{

	protected $protocol;
	protected $smtp_host;
	protected $smtp_port;
	protected $smtp_user;
	protected $smtp_pass;
	protected $smtp_auth;
	protected $smtp_name;
	protected $smtp_secure;
	protected $smtp_sender_from;
	protected $smtp_sender_name;


	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->config('smtp');
		$this->protocol = $this->CI->config->item('protocol');
		$this->smtp_host = $this->CI->config->item('smtp_host');
		$this->smtp_port = $this->CI->config->item('smtp_port');
		$this->smtp_user = $this->CI->config->item('smtp_user');
		$this->smtp_pass = $this->CI->config->item('smtp_pass');
		$this->smtp_auth = $this->CI->config->item('smtp_auth');
		$this->smtp_name = $this->CI->config->item('smtp_name');
		$this->smtp_secure = $this->CI->config->item('smtp_secure');
		$this->smtp_sender_from = $this->CI->config->item('smtp_sender_from');
		$this->smtp_sender_name = $this->CI->config->item('smtp_sender_name');

	}

	public function send($to, $subject, $message)
	{
		try {
			$mail = new PHPMailer(true);
			$mail->isSMTP(); // SMTP kullan
			$mail->Host = $this->smtp_host; // SMTP sunucu adresi
			$mail->SMTPAuth = $this->smtp_auth; // SMTP kimlik doğrulama kullan
			$mail->Username = $this->smtp_user; // SMTP kullanıcı adı
			$mail->Password = $this->smtp_pass; // SMTP şifre
			$mail->SMTPSecure = $this->smtp_secure; // Güvenli bağlantı türü - tls veya ssl kullanabilirsiniz
			$mail->Port = $this->smtp_port; // SMTP portu


			// Gönderici bilgileri
			$mail->setFrom($this->smtp_sender_from, $this->smtp_sender_name);

			// Alıcı bilgileri
			$mail->addAddress($to, 'Sayin Kullanici');

			// E-posta içeriği
			$mail->isHTML(true); // HTML biçiminde e-posta gönderme
			$mail->Subject = $subject; // E-posta konusu
			$mail->Body = $message; // E-posta içeriği HTML olarak

			$mail->send();
			return true;
		} catch (Exception $e) {
			//echo "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
			return false;
		}
	}

}
