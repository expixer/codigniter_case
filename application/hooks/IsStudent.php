<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IsStudent
{

	public function verify()
	{
		$CI =& get_instance();

		$CI->load->library('Authorization_Token');

		$controller = $CI->router->class;
		$method = $CI->router->method;
		$controllers_to_check = array('student');
		if (!in_array($controller, $controllers_to_check)) {
			return;
		}

		// Kullanıcı Student değilse 403 Forbidden döndür
		$headers = $CI->input->request_headers();
		$decodedToken = $CI->authorization_token->validateToken($headers['Authorization']);
		if ($decodedToken['status'] && $decodedToken['data']->role != 'student') {
			header('Content-Type: application/json');
			http_response_code(403);
			echo json_encode(array('error' => 'Yalnızca öğrenciler bu işlemi yapabilir.'));
			exit;
		}
	}
}
