<?php
	class MySqlConnection
	{
		//get connection
		public static function getConnection()
		{
			//read config
			$data = file_get_contents(__DIR__.'/../config/config.json');
			$config = json_decode($data, true);
			if (isset($config['mySqlConnection'])) {
				//get MySql settings
				$mySql = $config['mySqlConnection'];
				//parameters
				if (isset($mySql['server'])) 
					$server = $mySql['server']; 
				else { 
					echo 'Configuration error : MySql Server name not found'; 
					die; 
				}
				if (isset($mySql['user'])) 
					$user = $mySql['user']; 
				else { 
					echo 'Configuration error : User name not found'; 
					die; 
				} 
				if (isset($mySql['password'])) 
					$password = $mySql['password'];  
				else { 
					echo 'Configuration error : Password not found'; 
					die; 
				}
				if (isset($mySql['database'])) 
					$database = $mySql['database']; 
				else { 
					echo 'Configuration error : Database name not found'; 
					die; 
				}
				//open connection
				$connection = mysqli_connect($server, $user, $password, $database);
				//error in connection
				if ($connection === false) { 
					echo 'Could not connect to MySql'; 
					die; 
				}
				//character set 
				$connection->set_charset('utf8');
				//return connection object
				return $connection;
			}
			else {
				echo 'Configuration error : Could not find MySqlConnection settings';
				die;
			}
		}
	}
?>