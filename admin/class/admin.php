<?php

require_once 'config/dbconstants.php';

class Admin {

	private $id;

	public function __construct( $uid ) {
		$this->id = $uid;
	}

	public function getFeedbacks( $sub, $batch ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT t_id FROM teaches WHERE sub_id = ? AND batch_id = ?";
		$statement = $db->prepare( $query );
		$statement->bind_param( "ii", $sub, $batch );
		$statement->bind_result( $tid );
		$statement->execute();
		$statement->fetch();

		$result = $this->getConsolidatedFeedback( $tid );
		return $result;
	}

	private function getConsolidatedFeedback( $tid ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT COUNT(*), SUM(A1), SUM(A2), SUM(A3), SUM(A4), SUM(A5), SUM(A6), SUM(B1), SUM(B2), SUM(B3), SUM(B4), SUM(B5), SUM(B6), SUM(B7), SUM(B8), SUM(C1), SUM(C2), SUM(C3), SUM(C4), SUM(C5), SUM(C6), SUM(D1), SUM(D2), SUM(D3), SUM(D4), SUM(D5), SUM(D6), SUM(D7), SUM(D8), SUM(E1), SUM(E2), SUM(E3), SUM(E4), SUM(E5), SUM(E6), SUM(F1), SUM(F2), SUM(F3), SUM(F4), SUM(F5), SUM(F6), SUM(F7) FROM feedback WHERE t_id = ?";
		$statement = $db->prepare( $query );
		$statement->bind_param( "i", $tid );
		$statement->bind_result( $count, $A1, $A2, $A3, $A4, $A5, $A6, $B1, $B2, $B3, $B4, $B5, $B6, $B7, $B8, $C1, $C2, $C3, $C4, $C5, $C6, $D1, $D2, $D3, $D4, $D5, $D6, $D7, $D8, $E1, $E2, $E3, $E4, $E5, $E6, $F1, $F2, $F3, $F4, $F5, $F6, $F7 );
		$statement->execute();
		$statement->fetch();
		$result = array( $count, $A1, $A2, $A3, $A4, $A5, $A6, $B1, $B2, $B3, $B4, $B5, $B6, $B7, $B8, $C1, $C2, $C3, $C4, $C5, $C6, $D1, $D2, $D3, $D4, $D5, $D6, $D7, $D8, $E1, $E2, $E3, $E4, $E5, $E6, $F1, $F2, $F3, $F4, $F5, $F6, $F7 );
		return $result;
	}

	public function getDepartments() {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT dept_name, dept_id FROM department ORDER BY dept_name";
		$statement = $db->prepare( $query );
		$statement->bind_result( $name, $id );
		$statement->execute();

		$result = array();
		while( $statement->fetch() ) {
			$result[$id] = $name;
		}

		return $result;
	}

	public function getCourses( $dept ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT course_id, course_name FROM course WHERE dept_id = ? ORDER BY course_name";
		$statement = $db->prepare( $query );
		$statement->bind_param( "i", $dept );
		$statement->bind_result( $id, $name );
		$statement->execute();

		$result = array();
		while( $statement->fetch() ) {
			$result[ $id ] = $name;
		}

		return $result;
	}

	public function getBatches( $crs ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT batch_id, batch_name FROM batch WHERE course_id = ? ORDER BY batch_name";
		$statement = $db->prepare( $query );
		$statement->bind_param( "i", $crs );
		$statement->bind_result( $id, $name );
		$statement->execute();

		$result = array();
		while( $statement->fetch() ) {
			$result[ $id ] = $name;
		}

		return $result;
	}

	public function getSemesters( $crs ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT sem_id, sem_name FROM semester WHERE course_id = ? ORDER BY sem_name";
		$statement = $db->prepare( $query );
		$statement->bind_param( "i", $crs );
		$statement->bind_result( $id, $name );
		$statement->execute();

		$result = array();
		while( $statement->fetch() ) {
			$result[ $id ] = $name;
		}

		return $result;
	}

	public function getSubjects( $sem ) {
		$db = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );

		$query = "SELECT subject_id, subject_code, subject_name FROM subject WHERE sem_id = ? ORDER BY subject_code";
		$statement = $db->prepare( $query );
		$statement->bind_param( "i", $sem );
		$statement->bind_result( $id, $code, $name );
		$statement->execute();

		$result = array();
		while( $statement->fetch() ) {
			$result[ $id ] = $code.' - '.$name;
		}

		return $result;
	}
}

?>