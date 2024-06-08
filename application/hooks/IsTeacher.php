<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IsTeacher
{

	public function verify()
	{
		$CI =& get_instance();

		$CI->load->library('Authorization_Token');

		$controller = $CI->router->class;
		$method = $CI->router->method;

		$controllers_to_check = array('teacher');
		if (!in_array($controller, $controllers_to_check)) {
			return;
		}

		// Kullanıcı Teacher değilse 403 Forbidden döndür
		$headers = $CI->input->request_headers();
			$decodedToken = $CI->authorization_token->validateToken($headers['Authorization']);
			if ($decodedToken['status'] && $decodedToken['data']->role != 'teacher') {
				header('Content-Type: application/json');
				http_response_code(403);
				echo json_encode(array('error' => 'Yalnızca öğretmenler bu işlemi yapabilir.'));
				exit;
			}
	}
}
