<?php
	class StringIndexedValue {
		private $etiqueta;
		private $valor;
		
		public function __construct($etiqueta, $valor){
			$this->etiqueta = $etiqueta;
			$this->valor = $valor;
		}
		
		public function getEtiqueta(){
			return $this->etiqueta;
		}
		
		public function getValor(){
			return $this->valor;
		}
		
		public function setValor($novoValor){
			$this->valor = $novoValor;
		}
		
		public function addValor($novoValor){
			$this->valor += $novoValor;
		}

	}
	
	abstract class Identificavel {
		private $etiqueta;
		
		private function obtemIndiceEtiqueta($etiqueta, $parcial = false, $addMode = false){
			if (count($this->etiqueta) == 0){
				if (!$addMode) return -1;
				return 0;
			}
			
			$lbound = 0;
			$ubound = count($this->etiqueta) - 1;
			$mpoint = ($lbound + $ubound) / 2;
			$mpointObj = $this->etiqueta[$mpoint];
			$delta;
			
			while (($ubound >= $lbound && !$addMode) || ($ubound >= $lbound + 1 && $addMode)){
				$delta = strcmp($etiqueta, $mpointObj->getEtiqueta());
				if ($parcial && $delta != 0){
					$subtest = (strpos($mpointObj->getEtiqueta(), $etiqueta) !== false);
					if ($subtest) $delta = 0;
				}
				
				if ($delta == 1){
					$lbound = $mpoint + 1;
					$mpoint = ($lbound + $ubound) / 2;
					$mpointObj = $this->etiqueta[$mpoint];
				} else if (delta == -1){
					$ubound == $mpoint - 1;
					$mpoint = ($lbound + $ubound) / 2;
					$mpointObj = $this->etiqueta[$mpoint];
				} else if (!$addMode) {
					return $mpoint;
				}
			}
			
			if ($addMode){
				return $mpoint;
			}
			
			return -1;
		}
		
		private function __construct($mixedPar = NULL, $mixedPar2 = NULL){
			$this->etiqueta = array();
			if ($mixedPar != NULL){
				$this->addEtiqueta($mixedPar, $mixedPar2);
			}
		}
		
		public function addEtiqueta($mixedPar, $mixedPar2 = NULL){
			if (count($this->etiqueta) == 0){
				if (gettype($mixedPar) == "object"){
					array_push($mixedPar);
				}
			
				if (gettype($mixedPar) == "string" && (gettype($mixedPar2) == "double" || gettype($mixedPar2) == "integer")){
					array_push(new StringIndexedValue($mixedPar, $mixedPar2));
				}
				return;
			}
			
			$etiqueta;
			
			if (gettype($mixedPar) == "object"){
				$etiqueta = $mixedPar->getEtiqueta();
			}
			
			if (gettype($mixedPar) == "string"){
				$etiqueta = $mixdPar;
			}
			
			$getIndex = $this->obtemIndiceEtiqueta($etiqueta, false, true) + 1;
			$delta = strcmp($etiqueta, $this->etiqueta[$getIndex]->getEtiqueta());
			
			if ($delta == 1){
				array_splice($this->etiqueta, $getIndex, 0, $etiqueta);
			} else if ($delta == -1){
				array_splice($this->etiqueta, $getIndex - 1, 0, $etiqueta);
			} else {
				array_splice($this->etiqueta, $getIndex, 0, $etiqueta);
			}
		}
		
		public function possuiEtiqueta($etiqueta, $parcial = false){
			$getObj = $this->obtemIndiceEtiqueta($etiqueta, $parcial);
			if ($getObj != -1) return true;
			return false;
		}
		
		public function removeEtiqueta($etiqueta){
			if (count($this->etiqueta) == 0){
				return;
			}
			
			$getIndex = $this->obtemIndiceEtiqueta($etiqueta);
			array_splice($this->etiqueta, $getIndex, 1);
		}
		
		public function limpaEtiqueta(){
			unset($this->etiqueta);
			$this->etiqueta = array();
		}
		
		public function atribuiValor($etiqueta, $novoValor){
			$getIndex = $this->obtemIndiceEtiqueta($etiqueta);
			$getObj = $this->etiqueta[$getIndex];
			$getObj->setValor($novoValor);

		}
		
		public function adicionaValor($etiqueta, $novoValor){
			$getIndex = $this->obtemIndiceEtiqueta($etiqueta);
			$getObj = $this->etiqueta[$getIndex];
			$getObj->addValor($novoValor);
		}
		
		public function obtemValor($etiqueta){
			$getIndex = $this->obtemIndiceEtiqueta($etiqueta);
			$getObj = $this->etiqueta[$getIndex];
			return $getObj->getValor();
		}
		
		public function igual($id, $estrito = false){
			$i;
			$j;
			
			if ($estrito){
				if (count($this->etiqueta) != count($id->etiqueta)){
					return false;
				}
				
				for ($i = 0; $i < count($this->etiqueta); $i++){
					if (strcmp($this->etiqueta[$i], $id->etiqueta[$i]) != 0){
						return false;
					}
				}
				
				return true;
			}
			
			for ($i = 0; $i < count($this->etiqueta); $i++){
				for ($j = 0; $j < count($id->etiqueta); $j++){
					if (strcmp($this->etiqueta[$i]->getEtiqueta(), $this->etiqueta[$j]->getEtiqueta()) == 0){
						return true;
					}
				}
			}
			
			return false;
		}
		
		public function delta($id, $estrito = false){
			$i;
			$j;
			$delta;
			$delta2 = 0;
			$igual = self::igual($id, $estrito);
			if ($igual) return 0;
			
			for ($i = 0; $i < count($this->etiqueta); $i++){
				for ($j = 0; $j < count($id->etiqueta); $j++){
					$delta = strcmp($this->etiqueta[$i]->getEtiqueta(), $this->etiqueta[$j]->getEtiqueta());
					if ($estrito && $delta != 0) return $delta;

					if (!$estrito && delta == 0) return 0;
					else $delta2 = $delta;
				}
			}
			
			if ($estrito) return 0;
			return $delta2;
		}
		
		public function getNumEtiquetas(){
			return count($this->etiqueta);
		}
	}
?>