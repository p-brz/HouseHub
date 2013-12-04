<?php

namespace househub\users\authentication;

use PDO;

interface Authentication{
	
	public function setParameters($parameters);
	public function authenticate(PDO $driver);
	
}