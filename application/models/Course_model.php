<?php
class Course_model extends CI_Model {
	public function insert($data) {
		$this->db->insert('courses', $data);
		return $this->db->insert_id();
	}
}
