<?php

class CryptedMessage {

	var $cryptedText;
	var $transformations = array();
	
	function CryptedMessage($message) {
        $this->cryptedText = $message;
    }
	
	function decryptWithAlphabetFrequence($destinationAlphabetFrequence) {
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		
		$decryptedText = "";
		
		$cryptedTextAlphabetFrequence->orderByFrequence();
		$cryptedTextAlphabetFrequence->toString();

		//We replace letter by letter
		
		for($i=0; $i < strlen($this->cryptedText); $i++) {
			$lettre = substr($this->cryptedText, $i, 1);
			if($lettre != "\n") {
				//On récupère le classement de la lettre de la fréquence la plus haute vers la plus basse
				$position = $cryptedTextAlphabetFrequence->returnPositionLetterByHigherFrequence($lettre);
				//On cherche la correspondance en fréquence (en classement) dans l'alphabet destination
				$lettreDest = $destinationAlphabetFrequence->returnLetterByHigherFrequencePosition($position);
				//On effectue l'ajout de la lettre à notre texte décrypté
				$decryptedText .= $lettreDest;
				//echo $lettre."(".$position.") -> ".$lettreDest."<br/>";
			}
		}

		return $decryptedText;
	}
	
	/* Your transformation array must be like that model: {letter1, letter2} where we tranform letter1 to letter2*/
	function addTransformation($Letter1ToLetter2) {
		array_push($this->transformations, $Letter1ToLetter2);
	}
	
	/* The transformation will tranform letter1 to letter2*/
	function addTransformationLetters($Letter1, $Letter2) {
		array_push($this->transformations, array($Letter1, $Letter2));
	}
	
	function decryptWithTransformations() {
		$texteDecrypt = "";
		for($i=0; $i < strlen($this->cryptedText); $i++) {
			$lettre = substr($this->cryptedText, $i, 1);
			if($lettre != "\n") {
				$transformed = false;
				foreach ($this->transformations as $value){
					if($value[0] == $lettre) {
						$texteDecrypt.=$value[1];
						$transformed = true;
						break;
					}
				}
				if(!$transformed) 
					$texteDecrypt.=$lettre;
			}
		}
		return($texteDecrypt);
	}
	
	function calculIndiceCoincidence() {
		$sum = 0;
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		$n = strlen($this->cryptedText);
		
		foreach ($cryptedTextAlphabetFrequence->couplesLetterFrequence as $value){
			$nq = substr_count($this->cryptedText, $value['letter']);
			$sum += ($nq*($nq - 1)) / ($n*($n-1));
		}
		return $sum;
	}
}

?>