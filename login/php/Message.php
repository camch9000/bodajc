<?php
	class Message
	{
		private $message_id;
		private $create_date;
		private $user_send;
		private $user_receive;
		private $message;

        function __construct($id, $date, $send, $receive, $messa)
		{
			$this->$message_id = ($id != NULL ? $id : NULL);
			$this->$user_send = ($send != NULL ? $send : NULL);
			$this->$user_receive = ($receive != NULL ? $receive : NULL);
			$this->$create_date = ($date != NULL ? $date : NULL);
			$this->$message = ($messa != NULL ? $messa : NULL);
		}

		public function __set($var, $value)
		{
			$temp = strtolower($var);
			if(property_exists(Message, $temp))
				$this->$temp = $value;
		}

		public function __get($var)
		{
			$temp = strtolower($var);
			if(property_exists(Message, $temp))
				return $this->$temp;
			return NULL;
		}
	}
?>