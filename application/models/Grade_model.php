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

	public function check_if_midterm_and_final_given($student_id, $course_id)
	{
		$this->db->where('student_id', $student_id);
		$this->db->where('course_id', $course_id);
		$this->db->where('midterm IS NOT NULL');
		$this->db->where('final IS NOT NULL');
		$query = $this->db->get('grades');
		return $query->num_rows() > 0;
	}

	public function update_average_and_letter()
	{
		$this->db->query('UPDATE grades SET average = midterm * 0.4 + final * 0.6 WHERE midterm IS NOT NULL AND final IS NOT NULL');
		$this->db->query('UPDATE grades SET letter = CASE
			WHEN average >= 90 THEN "AA"
			WHEN average >= 80 THEN "AB"
			WHEN average >= 75 THEN "BA"
			WHEN average >= 65 THEN "BB"
			WHEN average >= 60 THEN "CB"
			WHEN average >= 50 THEN "CC"
			WHEN average >= 45 THEN "DD"
			ELSE "F"
			END
			WHERE midterm IS NOT NULL AND final IS NOT NULL');
	}
}
