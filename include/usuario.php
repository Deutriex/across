<?php
	include("databaseConnection.php");
	
	class Usuario {
		private static $database = null;
		private $tipo;
		private $id;
		private $username;
		
		public function __construct($id){
			if (self::$database == null){
				self::$database = DatabaseConnection::getDBObject();
			}
			
			$this->id = $id;
			
			$query = "SELECT * FROM users WHERE idnum=" . $this->id . ";";
			
			$result = mysqli_query(self::$database, $query);
			$row = mysqli_fetch_array($result);
			
			$this->tipo = $row['type'];
			$this->username = $row['username'];
		}
		
		public function getId(){
			return $this->id;
		}
		
		public function getTipo(){
			return $this->tipo;
		}
		
		public function getUsername(){
			return $this->username;
		}
		
		public function getSubmissions($idTarefa){
			$query = "SELECT COUNT(*) as totalSubs FROM taskhistory WHERE idtask=" . $idTarefa . " AND idcrowdsourcer=" . $this->id . " AND gold=1;";
			$res = mysqli_query(self::$database, $query);
			$rows = mysqli_fetch_array($res);
			return $rows['totalSubs'];
		}
		
		public function getTrustScore($idTarefa, $screenFormat = false){
			$query = "SELECT * FROM taskhistory WHERE idtask=" . $idTarefa . " AND idcrowdsourcer=" . $this->id . " AND gold=1;";
			$correctTq = 0;
			$totalTq = 0;
			$res = mysqli_query(self::$database, $query);
			
			while ($row = mysqli_fetch_array($res)){
				$query2 = "SELECT * FROM goldtaskhistory WHERE idnumref=" . $row['idnum'] . ";";
				$res2 = mysqli_query(self::$database, $query2);
				$row2 = mysqli_fetch_array($res2);
				
				$correctTq += $row2['correct'];
				$totalTq++;
			}
			
			if ($totalTq != 0) 	$acc = ($correctTq/$totalTq);
			else 				$acc = 1;
			
			if ($screenFormat){
				$acc = floor(1000*$acc)/10;
			}
			
			return $acc;
		}
		
		public function hasFlagOn($idTarefa){
			$query2 = "SELECT COUNT(*) as flagged FROM flaghistory WHERE idcrowdsourcer=" . $this->id . " AND idtask=" . $idTarefa . ";";
			$res2 = mysqli_query(self::$database, $query2);
			$row2 = mysqli_fetch_array($res2);
			
			return $row2['flagged']; 	
			
		}
		
	}
?>