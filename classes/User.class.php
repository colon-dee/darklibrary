<?php
class User extends Record {

	const TABLE = 'Users'; // table name
	const PK = 'codUser'; // primary key

	/**
	 * Validate and search for an user data from their
	 * username and password. This is the conventional
	 * method to perform an user login.
	 * @param  	string 		$username
	 * @param  	string 		$password
	 * @return 	mixed
	 */
	static function logIn($username = null, $password = null) {
		// Verify if user and password were defined
		if ($username && $password) {
			$userVerify = User::userVerify($username, $password);
			$data = $userVerify['data'];

			// Verify whether there's an user with this username and password
			if ($data) {

				// Verify whether user was approved by the community
				if (!$data->approved) {
					return "l2";
				}

				$_SESSION['user']['codUser'] = $data->codUser;
				$_SESSION['user']['loggedin'] = true;

				return 0;
			} else {
				// Incorrect username/password
				return "l1";
			}
		} else {
			return "l0";
		}
	}

	/**
	 * Search for an user with corresponding username and password in the database
	 * @param  string 	$user
	 * @param  string 	$password
	 * @return array          		Query result
	 */
	static function userVerify($username, $password) {
		$userExists = false;
		$where = array("name = ?", $username);
		$options = array(
			'limit' =>  1
		);

		// Verify whether user exists
		$user = User::find($where, $options);

		if (!empty($user)) {
			$userExists = true;

			// Verify whether the passwords match
			if (!password_verify($password , $user[0]->password)) {
				$user[0] = null; // Deny access to users data
			}
		}

		return array('exists' => $userExists, 'data' => $user[0]);
	}
}
?>
