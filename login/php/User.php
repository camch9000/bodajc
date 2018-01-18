<?php
	class User
	{
		private $user_id;
		private $user_name;
		private $user_pass;
		private $create_date;

        function __construct($id, $name, $pass, $date)
		{
			$this->user_id = ($id != NULL ? $id : NULL);
			$this->user_name = ($name != NULL ? $name : NULL);
			$this->user_pass = ($pass != NULL ? $pass : NULL);
			$this->create_date = ($date != NULL ? $date : NULL);
		}

		public function __set($var, $value)
		{
			$temp = strtolower($var);
			if(property_exists(User, $temp))
				$this->$temp = $value;
		}

		public function __get($var)
		{
			$temp = strtolower($var);
			if(property_exists(User, $temp))
				return $this->$temp;
			return NULL;
		}
	}
?>