<?php
final class ConnectionFactory {

	private static $cache = array();

	private function __construct() {
		// Prevent the class from being instantiated
	}

	/**
	 * Create DB connection.
	 * @return PDO
	 */
	static function getConnection($env = null) {
		global $connection;

		// Set $def_cred->env from config.php as default environment
		if (empty($env)) {
			global $def_cred;
			$env = $def_cred->env;
		}

		// Does not create connection if it was already created
		if (empty(self::$cache[$env])) {
			$config = $connection[$env];

			if (empty($config)) {
				throw new Exception(getErrorMessage("c2", $env));
			}

			// Create PDO Object
			$host = $config['host'];
			$db = $config['db'];
			$user = $config['user'];
			$pass = $config['pass'];
			$connection = new PDO("mysql:host=$host;dbname=$db", $user, $pass);

			// Set UTF-8 as used encoding
			$connection->exec("SET NAMES 'utf8'");
			$connection->exec('SET character_set_connection=utf8');
			$connection->exec('SET character_set_client=utf8');
			$connection->exec('SET character_set_results=utf8');

			// Throw an exception in case of SQL error
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// Store connection in cache
			self::$cache[$env] = $connection;
		}

		return self::$cache[$env];
	}

}
?>
