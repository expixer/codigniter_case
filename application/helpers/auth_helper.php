<?php
if (!function_exists('extract_user_from_token')) {
	function extract_user_from_token()
	{
		$CI = &get_instance();
		$CI->load->library('Authorization_Token');
		$headers = $CI->input->request_headers();
		$decoded = $CI->authorization_token->validateToken($headers['Authorization']);
		return $decoded['data'];
	}
}
