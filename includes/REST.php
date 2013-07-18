<?php

class REST
{
	private $request_method = '';
	private $inputs = array();
	
	public function __construct()
	{
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->get_inputs($this->request_method);
	}
	
	public function get_method()
	{
		return $this->request_method;
	}
	
	public function get_parameters()
	{
		return $this->inputs;
	}
	
	private function get_inputs($method)
	{
		if($method == "GET")//ifs are slightly faster in PHP than switch statements
		{
			$this->inputs = $_GET;
		}
		else if($method == "POST")
		{
			$this->inputs = $_POST;
		}
		else if($method == "DELETE")
		{
			$this->inputs = $_GET;
		}
		else if($method == "PUT")
		{
			parse_str(file_get_contents("php://input"), $this->inputs);
		}
		else
		{
			$this->respond(null, '406', 'Not acceptable');
		}
	}
	
	public function respond($code, $string_data, $location = '')
	{		
		if($location == '')
		{
			header("HTTP/1.1 ".$code);
		}
		else
		{
			header('Location: '.$location);
			die();
		}
		
		echo $string_data;
		die();
	}
}

?>