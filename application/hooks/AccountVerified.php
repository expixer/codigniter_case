<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AccountVerified
{

	public function verify()
	{
		$CI =& get_instance();
		$CI->load->library('Authorization_Token');

		// Controller ve method adlarını al
		$controller = $CI->router->class;
		$method = $CI->router->method;

		// Sadece belirli controller ve metodlar için kontrol
		$controllers_to_check = array('Student', 'Teacher');
		if (!in_array($controller, $controllers_to_check)) {
			return;
		}


		// Authorization başlığı yoksa 401 Unauthorized döndür
		$headers = $CI->input->request_headers();
		if (!isset($headers['Authorization'])) {
			header('Content-Type: application/json');
			http_response_code(401);
			echo json_encode(array('error' => 'Authorization başlığı eksik.'));
			exit;
		}


		// Token geçersizse 401 Unauthorized döndür
		$decoded = $CI->authorization_token->validateToken($headers['Authorization']);
		if (!$decoded["status"]) {
			header('Content-Type: application/json');
			http_response_code(401);
			echo json_encode(array('error' => 'Geçersiz token.'));
			exit;
		}

		// Kullanıcı onaylanmamışsa 403 Forbidden döndür
		$CI->load->model('User_model');
		$user = $CI->User_model->get_user($decoded['data']->id);
		if (!$user || !$user->is_confirmed) {
			header('Content-Type: application/json');
			http_response_code(403);
			echo json_encode(array('error' => 'Lütfen hesabınızı onaylayın.'));
			exit;
		}

	}
}
