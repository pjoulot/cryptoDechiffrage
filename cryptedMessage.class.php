<?php

class CryptedMessage {

	var $cryptedText;
	var $transformations = array();
	var $alphabetFrequence;
	var $sousTextes = array();
	
	function CryptedMessage($message) {
        //On remplit le message
		$this->cryptedText = $message;
		
		//On remplit l'alphabet fréquence
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		$cryptedTextAlphabetFrequence->orderByFrequence();
		$this->alphabetFrequence = $cryptedTextAlphabetFrequence;
    }
	
	function decryptWithAlphabetFrequence($destinationAlphabetFrequence) {
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		
		$decryptedText = "";
		
		$cryptedTextAlphabetFrequence->orderByFrequence();
		$this->alphabetFrequence = $cryptedTextAlphabetFrequence;

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
	
	function analizeFrequenceSubtexts() {
		foreach ($this->sousTextes as $value){
			echo $value->cryptedText."<br/>";
			$value->alphabetFrequence->toString();
		}
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
	
	function decoupageTexte($tailleCle) {
		$sum = 0;
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		$tab = array();
		for($i=0;$i<$tailleCle;$i++) {
			$tab[$i] = "";
		}
		//On sépare notre texte suivant la taille de la clé
		$tailleText = strlen($this->cryptedText);
		for($i=0;$i<$tailleText;$i++) {
			$lettre = substr($this->cryptedText, $i, 1);
			$tab[$i%$tailleCle] .= $lettre;
		}
		
		$this->sousTextes = array();
		foreach ($tab as $value){
			array_push($this->sousTextes, new CryptedMessage($value));
		}
	}
	
	function decryptWithDecoupage($destinationAlphabetFrequence) {
		$tab = array();
		$i = 0;
		//On traduit les sous-textes découpés par la taille de clé un à un 
		foreach ($this->sousTextes as $value){
			$tab[$i] = $value->decryptWithAlphabetFrequence($destinationAlphabetFrequence);
			$i++;
		}
		
		//On assemble les morceaux en un seul texte
		$tailleText = strlen($this->cryptedText);
		$texteDecrypte = "";
		for($i=0;$i<strlen($tab[0]);$i++) {
			for($j = 0; $j < count($tab); $j++) {
				if($i <= strlen($tab[$j])) {
					$lettre = substr($tab[$j], $i, 1);
					$texteDecrypte .= $lettre;
				}
			}
		}
		return $texteDecrypte;
	}
	
	function decryptDecoupageWithKey($alphabet, $key) {
		$tab = array();
		$i = 0;
		//On traduit les sous-textes découpés par la taille de clé un à un 
		foreach ($this->sousTextes as $value){
			$decalage = $key[$i];
			$traduced = "";
			for($j=0;$j<strlen($value->cryptedText);$j++) {
				$lettre = substr($value->cryptedText, $j, 1);
				$k =0;
				foreach ($alphabet->elements as $letter){
					if($letter == $lettre) {
						break;
					}
					$k++;
				}
				$traduced .= $alphabet->elements[($k + $decalage)%count($alphabet->elements)];
			}
			$tab[$i] = $traduced;
			$i++;
		}
		
		//On assemble les morceaux en un seul texte
		$tailleText = strlen($this->cryptedText);
		$texteDecrypte = "";
		for($i=0;$i<strlen($tab[0]);$i++) {
			for($j = 0; $j < count($tab); $j++) {
				if($i <= strlen($tab[$j])) {
					$lettre = substr($tab[$j], $i, 1);
					$texteDecrypte .= $lettre;
				}
			}
		}
		return $texteDecrypte;
	}
	
	function brutForceDecoupageCesar($alphabet) {
		/* Prétraitement séquentiel */
		$sousTextesDecales = array();
		foreach($this->sousTextes as $sousTexte) {
			array_push($sousTextesDecales, $sousTexte->brutForceCesar($alphabet));
		}
		/*
		var_dump($sousTextesDecales);
		
		$sousTextesDecales = array();
		$sousTextesDecales[0] = array("POM", "API", "POI");
		$sousTextesDecales[1] = array("REN", "FEU", "ABR");
		$sousTextesDecales[2] = array("MER", "COQ", "SEL");
		$sousTextesDecales[3] = array("SA", "BO", "BI");
		*/
		/* Traitement récursif */
		$this->brutForceDecoupageCesarBis($sousTextesDecales, array(), 0);
		
	}
	
	function brutForceDecoupageCesarBis($sousTextesDecales, $chaine, $level) {
		foreach($sousTextesDecales[$level] as $id => $sousTexteDecale) {
					if($level == count($sousTextesDecales)-1) {
						$chaine2 = $chaine;
						array_push($chaine2, $sousTexteDecale);
						//var_dump($chaine2);
						//echo($chaine.$sousTexteDecale."<br/>");
						//Il faut remettre la chaine dans l'ordre
						$chaineFinale = "";
						$tailleText = strlen($this->cryptedText);
						$positionLetter = 0;
						for($i=0;$i<$tailleText;$i++) {
							$lettre = substr($chaine2[$i%count($chaine2)], $positionLetter, 1);
							$chaineFinale .= $lettre;
							if($i%(count($chaine2)) == count($chaine2)-1) {
								$positionLetter++;
							}
						}
						$myFile= fopen("brutForce.txt", "a");
						fwrite($myFile, $chaineFinale."\n");
						fclose($myFile);
						//echo $chaineFinale."<br/>";
					}
					else {
						$chaine2 =  $chaine;
						array_push($chaine2, $sousTexteDecale);
						$this->brutForceDecoupageCesarRecurBis($sousTextesDecales, $chaine2, $level+1);
					}
		}
	}
	
	function brutForceCesar($alphabet) {
		$tailleAlphabet = count($alphabet->elements);
		$textesDecodes = array();
		for($i = 0; $i < $tailleAlphabet; $i++) {
			$textDecale = "";
			$tailleText = strlen($this->cryptedText);
			for($j=0;$j<$tailleText;$j++) {
				$lettre = substr($this->cryptedText, $j, 1);
				$positionLetter = 0;
				foreach($alphabet->elements as $value) {
					if($lettre == $value) {
						break;
					}
					$positionLetter++;
				}
				$textDecale .= $alphabet->elements[($positionLetter + $i)%$tailleAlphabet];
			}
			array_push($textesDecodes, $textDecale);
		}
		return ($textesDecodes);
	}
	
	function calculIndiceCoincidence($tailleCle) {
		$sum = 0;
		$cryptedTextAlphabetFrequence = new AlphabetFrequence;
		$cryptedTextAlphabetFrequence->createAlphabetFrequenceFromText($this->cryptedText);
		$tab = array();
		for($i=0;$i<$tailleCle;$i++) {
			$tab[$i] = "";
		}
		//On sépare notre texte suivant la taille de la clé
		$tailleText = strlen($this->cryptedText);
		for($i=0;$i<$tailleText;$i++) {
			$lettre = substr($this->cryptedText, $i, 1);
			$tab[$i%$tailleCle] .= $lettre;
		}
		
		//On calcule l'indice de coincidence de chaque colonne de notre clé puis on fait une moyenne
		$moy = 0;
		foreach ($tab as $value){
			$sum = 0;
			$n = strlen($value);
			foreach ($cryptedTextAlphabetFrequence->couplesLetterFrequence as $myalphabetletter){
				if(substr_count($value, $myalphabetletter['letter']) > 0) {
					$nq = substr_count($value, $myalphabetletter['letter']);
					$sum += ($nq*($nq - 1)) / ($n*($n-1));
				}
			}
			
			$moy+=$sum;
		}
		$moy= $moy / $tailleCle;
		return $moy;
	}
}

?>