<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* On your database, open a SQL terminal paste this and execute: */
// CREATE TABLE IF NOT EXISTS `users` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `username` varchar(255) NOT NULL DEFAULT '',
//   `email` varchar(255) NOT NULL DEFAULT '',
//   `password` varchar(255) NOT NULL DEFAULT '',
//   `avatar` varchar(255) DEFAULT 'default.jpg',
//   `created_at` datetime NOT NULL,
//   `updated_at` datetime DEFAULT NULL,
//   `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   `is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   PRIMARY KEY (`id`)
// );
// CREATE TABLE IF NOT EXISTS `ci_sessions` (
//   `id` varchar(40) NOT NULL,
//   `ip_address` varchar(45) NOT NULL,
//   `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
//   `data` blob NOT NULL,
//   PRIMARY KEY (id),
//   KEY `ci_sessions_timestamp` (`timestamp`)
// );


require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller
{


	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		$this->load->library('Smtp');
		$this->load->model('user_model');
	}


	public function register_post()
	{

		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|is_unique[users.username]', array('is_unique' => 'This username already exists. Please choose another one.'));
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('surname', 'Surname', 'trim|required');
		//$this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');

		if ($this->form_validation->run() === false) {
			$errors = $this->form_validation->error_array();
			$this->response($errors, REST_Controller::HTTP_BAD_REQUEST);

		} else {

			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$activation_key = md5($email . time() . $username);
			$role = 'teacher';

			if ($res = $this->user_model->create_user($username, $email, $password, $activation_key, $role)) {

				$token_data['id'] = $res;
				$token_data['role'] = $role;
				$token_data['username'] = $username;
				$tokenData = $this->authorization_token->generateToken($token_data);
				$final = array();
//				$final['access_token'] = $tokenData;
				$final['status'] = true;
				$final['id'] = $res;
				$final['role'] = $res;
				$final['message'] = 'Kayıt başarılı';
				$final['note'] = 'Hesabınız oluşturuldu, giriş yapmak için epostanıza gönderilen aktivasyon linkine tıklayın.';

				// send email
				$subject = 'Hesap Aktivasyonu';
				$message = 'Merhaba ' . $username . ',<br><br>Hesabınızı aktive etmek için aşağıdaki linke tıklayın:<br><br><a href="' . base_url() . 'activate/' . $activation_key . '">Hesabı Aktifleştir</a>';
				$this->smtp->send($email, $subject, $message);

				$this->response($final, REST_Controller::HTTP_OK);

			} else {

				$this->response(['Hesabınız oluşturulurken bir sorunla karşılaşıldı. Tekrar deneyin.'], REST_Controller::HTTP_OK);
			}

		}

	}

	public function activate_get($activation_key)
	{

		if ($this->user_model->activate_user($activation_key) > 0) {

			$this->response(['Hesabınız başarıyla aktive edildi.'], REST_Controller::HTTP_OK);

		} else {

			$this->response(['Geçersiz aktivasyon kodu veya zaten aktif edilmiş.'], REST_Controller::HTTP_NOT_FOUND);

		}

	}


	public function login_post()
	{

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if (!$this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->response($errors, REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if ($this->user_model->resolve_user_login($username, $password)) {

				$user_id = $this->user_model->get_user_id_from_username($username);
				$user = $this->user_model->get_user($user_id);

				// user login ok
				$token_data['id'] = $user_id;
				$token_data['role'] = $user->role;
				$token_data['username'] = $user->username;
				$tokenData = $this->authorization_token->generateToken($token_data);
				$final = array();
				$final['access_token'] = $tokenData;
				$final['status'] = true;
				$final['message'] = 'Giriş başarılı!';
				$final['note'] = 'Hesabınıza giriş yaptınız.';

				$this->response($final, REST_Controller::HTTP_OK);

			} else {

				$this->response(['Kullanıcı adı veya parola hatalı.'], REST_Controller::HTTP_OK);

			}

		}

	}

	public function logout_post()
	{

		if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

			foreach ($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}

			$this->response(['Logout success!'], REST_Controller::HTTP_OK);

		} else {

			$this->response(['Bir problem oldu. Tekrar deneyin.'], REST_Controller::HTTP_OK);
		}

	}

}
