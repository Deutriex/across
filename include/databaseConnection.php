<?php

if (!class_exists("DatabaseConnection")){
	class DatabaseConnection {
		private static $database = NULL;
		
		private function __construct(){
		}
		
		public static function getDBObject(){
			if (self::$database == NULL){
				self::$database = mysqli_connect("localhost","root","*EvitanRetla3055-2","across");
				mysqli_query(self::$database,"SET NAMES 'utf8'");
				mysqli_query(self::$database,'SET character_set_connection=utf8');
				mysqli_query(self::$database,'SET character_set_client=utf8');
				mysqli_query(self::$database,'SET character_set_results=utf8');
		
			}
			
			return self::$database;
		}
	}
}

?>