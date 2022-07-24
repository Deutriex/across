<?php
	include("identificavel.php");
	abstract class Enumeravel extends Identificavel {
		private static $idEnumeravel = -1;
		private static $objAtual = NULL;
		protected static $numInstancias = 0;
		protected static $iterators = array();
		protected static $tempObjects = array();
		protected static $instances = array();
		protected static $instanceCount = array();
		
		protected function __construct($etiqueta = NULL){
			parent::__construct($etiqueta);
		}
		
		protected static function adicionarIterador($intVar){
			while (count(self::$iterators[self::$idEnumeravel]) <= $intVar){
				array_push(self::$iterators[self::$idEnumeravel], 0);
			}
		}
		
		protected static function adicionarInstancia($intVar){
			while (count(self::$instances) <= $intVar){
				array_push(self::$instances, array());
			}
			
			while (count(self::$iterators) <= $intVar){
				array_push(self::$iterators, array());
			}
			
			while (count(self::$instanceCount) <= $intVar){
				array_push(self::$instanceCount, 0);
			}
			
			while (count(self::$tempObjects) <= $intVar){
				array_push(self::$tempObjects, NULL);
			}
		}
		
		protected static function obtemInstanciaDe(){
			if (self::$idEnumeravel >= count(self::$instances)) self::adicionarInstancia(self::$idEnumeravel);
			if (count(self::$instances[self::$idEnumeravel]) == 0){
				return NULL;
			}
			
			$lbound = 0;
			$ubound = count(self::$instances[self::$idEnumeravel]) - 1;
			$mpoint = ($lbound + $ubound) / 2;
			$mPointObj = self::$instances[self::$idEnumeravel][$mpoint];
			$delta = 0;
			
			while ($ubound >= $lbound){
				$delta = $objAtual->compFunction($mPointObj);
				
				if ($delta == -1){
					$ubound = $mpoint - 1;
					$mpoint = ($lbound + $ubound) / 2;
					$mPointObj = self::$instances[self::$idEnumeravel][$mpoint];
				} else if ($delta == 1){
					$lbound = $mpoint + 1;
					$mpoint = ($lbound + $ubound) / 2;
					$mPointObj = self::$instances[self::$idEnumeravel][$mpoint];
				} else {
					return $mPointObj;
				}
			}
			
			return NULL;
		}
		
		protected static function obtemInstanciaDeComum(){
			if (self::$idEnumeravel >= count($this->instances)) self::adicionarInstancia(self::$idEnumeravel);
			$tempT = self::$tempObjects[self::$idEnumeravel];
			$tempT->addEtiqueta("TMPOBJ");
			self::setIdObjetoAtual($tempT);
			$el = self::obtemInstanciaDe();
			if ($el == NULL){
				$tempT->removeEtiqueta("TMPOBJ");
				self::adicionarNovaInstancia($tempT);
				return $tempT;
			} else {
				unset($tempT);
				return $el;				
			}
		}
		
		protected static function adicionarNovaInstancia($enumObj){
			if (count(self::$instances[self::$idEnumeravel]) == 0){
				array_push(self::$instances[self::$idEnumeravel], $enumObj);
				return true;
			}
			
			$lbound = 0;
			$ubound = count(self::$instances[self::$idEnumeravel]) - 1;
			$mpoint = ($lbound + $ubound) / 2;
			$delta;
			$mpointObj = self::$instances[self::$idEnumeravel][$mpoint];
			
			while ($ubound - $lbound >= 1){
				$delta = $enumObj->compFunction($mpointObj);
				if ($delta == -1){
					$ubound = $mpoint - 1;
					$mpoint = ($lbound + $ubound)/2;
					$mpointObj = self::$instances[self::$idEnumeravel][$mpoint];	
				} else if ($delta == 1){
					$lboubd = $mpoint + 1;
					$mpoint = ($lbound + $ubound) / 2;
					$mpointObj = self::$instances[self::$idEnumeravel][$mpoint];	
				} else {
					return false;
				}
			}
			
			$elIt += $mpoint + 1;
			
			if ($enumObj[$mpointObj] == 1){
				array_splice(self::$instances[self::$idEnumeravel], $elIt, 0, $enumObj);				
				return true;
			} else if ($enumObj[$mpointObj] == -1){
				array_splice(self::$instances[self::$idEnumeravel], $elIt - 1, 0, $enumObj);				
				return true;
			} else {
				array_splice(self::$instances[self::$idEnumeravel], $elIt, 0, $enumObj);				
				return true;
			}
			
			return false;
		}
		
		abstract protected function compFunctionComum($enumObj);
		
		public function compFunction($enumObj = NULL){
			if (gettype($mxObj) == "object"){
				return $this->compFunctionComum($enumObj);
			}
		}

		public static function setIdObjetoAtual($mxObj){
			if (gettype($mxObj) == "integer"){
				self::$idEnumeravel = $mxObj;
			}
			
			if (gettype($mxObj) == "object"){
				self::$objAtual = $mxObj;
				self::$idEnumeravel = $mxObj->getIdEnumeravel();
			}
		}		
		
		abstract public function getIdEnumeravel();
		
		public static function getObjTemporario(){
			return self::$tempObjects[self::$idEnumeravel];
		}
		
		public static function setObjTemporario($enumObj){
			self::$idEnumeravel = $enumObj->getIdEnumeravel();
			self::$tempObjects[self::$idEnumeravel] = $enumObj;
		}
		
		public static function getIdObjetoAtual(){
			return self::$idEnumeravel;
		}
		
		public static function moverParaInicio($intVar){
			if (count(self::$iterators[self::$idEnumeravel]) <= $intVar) self::adicionarIterador($intVar);
			self::$iterators[self::$idEnumeravel][$intVar] = 0; 
		}
		
		public static function moverParaProixmo($intVar){
			if (count(self::$iterators[self::$idEnumeravel]) <= $intVar) self::adicionarIterador($intVar);
			return ++self::$iterators[self::$idEnumeravel][$intVar];
		}
		
		public static function noFim($intVar){
			if (count(self::$iterators[self::$idEnumeravel]) <= $intVar) self::adicionarIterador($intVar);
			return self::$iterators[self::$idEnumeravel][$intVar] >= count(self::$instances[self::$ifEnumeravel]);
		}
		
		public static function obtemObjetoAtual($intVar){
			if (count(self::$iterators[self::$idEnumeravel]) <= $intVar) self::adicionarIterador($intVar);
			
			if (self::$iterators[self::$idEnumeravel] < count(self::$instances[$idEnumeravel])){
				$itValue = self::$iterators[self::$idEnumeravel][$intVar];
				$returnThis = $instances[self::$idEnumeravel][$intVar];
				return $returnThis;
			} else {
				return NULL;
			}
		}
		
		public static function obtemNumInstancias(){
			if (self::$idEnumeravel >= count(self::$instances)) self::adicionarInstancia(self::$idEnumeravel);
			return count(self::$instances[self::$idEnumeravel]);
		}
		
		public static function obterTodosComEtiqueta($etiqueta, $parcial = false){
			$i;
			$res = array();
			
			for ($i = 0; $i < count(self::$instances[self::$idEnumeravel]); $i++){
				$candidate = self::$instances[self::$idEnumeravel][$i];
				if ($candidate != NULL && $candiate->possuiEtiqueta($etiqueta, $parcial)){
					array_push($res, $candiate);
				}
			}
			
			return $res;
		}
		
		public static function alocaRetornaIndice(){
			if (self::$instances == NULL)		self::$instances = array();
			if (self::$iterators == NULL)		self::$iterators = array();
			if (self::$instanceCount == NULL)	self::$instanceCount = array();
			if (self::$tempObjects == NULL)		self::$tempObjects = array();
			
			$valorRetorno = count(self::$instances);
			
			array_push(self::$instances, array());
			array_push(self::$iterators, array());
			array_push(self::$instanceCount, array());
			array_push(self::$tempObjects, NULL);
			
			return $valorRetorno;
		}
		
		public function __destruct(){
			if (count(self::$instances[self::$idEnumeravel]) == 0) return;
			
			$encontrado = false;
			$lbound = 0;
			$ubound = count(self::$instances[self::$idEnumeravel]) - 1;
			$mpoint = ($lbound + $ubound) / 2;
			$mpointObj = self::$instances[self::$idEnumeravel][$mpoint];
			$delta = 0;
			
			while ($ubound >= $lbound && !$encontrado){
				if ($mpointObj == $this){
					array_splice(self::$instances[self::$idEnumeravel], self::$instances[self::$idEnumeravel][$mpoint], 1);
					return;
				}
				
				$delta = $mpointObj->compFuncion($this);
				
				if ($delta == -1){
					$lbound = $mpoint + 1;
				} else if ($delta == 1){
					$ubound = $mpoint - 1;
				} else if ($delta == 0){
					if ($mpointObj == $this){
						array_splice(self::$instances[self::$idEnumeravel], self::$instances[self::$idEnumeravel][$mpoint], 1);
					}
					
					$encontrado = true;
					return;
				} else {
					return;
				}
				
				$mpoint = ($lbound + $ubound) / 2;
				$mpointObj = self::$instances[self::$idEnumeravel][$mpoint];
			}
			
		}
		
		

	}
?>