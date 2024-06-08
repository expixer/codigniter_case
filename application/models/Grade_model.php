<?php

class Grade_model extends CI_Model
{
	public function insert($data)
	{
		$this->db->insert('grades', $data);
		return $this->db->insert_id();
	}

	public function get_by_student($student_id)
	{
		$this->db->select('courses.name as course_name, grades.midterm, grades.final, grades.average, grades.letter');
		$this->db->from('grades');
		$this->db->join('courses', 'courses.id = grades.course_id');
		$this->db->where('grades.student_id', $student_id);
		return $this->db->get()->result();
	}

	public function get_by_student_and_course($student_id, $course_id)
	{
		$this->db->select('courses.name as course_name, grades.midterm, grades.final, grades.average, grades.letter, grades.student_id, grades.course_id');
		$this->db->from('grades');
		$this->db->join('courses', 'courses.id = grades.course_id');
		$this->db->where('grades.student_id', $student_id);
		$this->db->where('grades.course_id', $course_id);
		return $this->db->get()->result();
	}

	public function get_by_teacher($teacher_id)
	{
		$this->db->select('courses.name as course_name, users.name as student_name, grades.midterm, grades.final, grades.average, grades.letter');
		$this->db->from('grades');
		$this->db->join('courses', 'courses.id = grades.course_id');
		$this->db->join('users', 'users.id = grades.student_id');
		$this->db->join('teacher_courses', 'teacher_courses.course_id = courses.id');
		$this->db->where('teacher_courses.teacher_id', $teacher_id);
		return $this->db->get()->result();
	}

	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('grades', $data);
	}

	public function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('grades');
	}

	public function get_by_course($course_id)
	{
		$this->db->select('users.name as student_name, grades.midterm, grades.final, grades.average, grades.letter');
		$this->db->from('grades');
		$this->db->join('users', 'users.id = grades.student_id');
		$this->db->where('grades.course_id', $course_id);
		return $this->db->get()->result();
	}

	public function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('grades')->row();
	}
}
