<?php

class AlphabetFrequence {

	var $couplesLetterFrequence = array();
	
	/* Use when you have frequencies which are already known */
	function createAlphabetFrequence($varAlphabet, $varFrequences) {
		if(count($varAlphabet->elements) == count($varFrequences)) {
			$i = 0;
			foreach ($varAlphabet->elements as $value){
				 array_push($this->couplesLetterFrequence, array("letter" => $value, "frequence" => $varFrequences[$i]));
				 $i++;
			}
		}
    }
	
	/* Use when you want to use a text to generate your frequencies and your alphabet */
	function createAlphabetFrequenceFromText($texte) {
		$taille = strlen($texte);
		
		for($i=0; $i < $taille; $i++) {
			$lettre = substr($texte, $i, 1);
			if($lettre != "\n") {
				if(!$this->letterIsPresent($lettre)) {
					array_push($this->couplesLetterFrequence, array("letter" => $lettre, "frequence" => (substr_count($texte, $lettre))/$taille));
				}
			}
		}
    }
	
	function letterIsPresent($letter) {
		foreach ($this->couplesLetterFrequence as $value){
			if( $value['letter'] == $letter)
				return true;
		}
		return false;
	}
	
	function removeCoupleFromLetter($letter) {
		$done = false;
		foreach ($this->couplesLetterFrequence as $key => $value){
			if( $value['letter'] == $letter) {
				unset($this->couplesLetterFrequence[$key]);
				$done = true;
			}
		}
		return $done;
	}
	
	function returnMaxFrequenceLetter() {
		$max = 0.0;
		$maxLetter = null;
		foreach ($this->couplesLetterFrequence as $value){
			if( $value['frequence'] > $max) {
				$max = $value['frequence'];
				$maxLetter = $value['letter'];
			}
		}
		return($maxLetter);
	}
	
	function returnPositionLetterByHigherFrequence($letter) {
		$position = -1;
		$tmp = clone $this;
		foreach ($tmp->couplesLetterFrequence as $value){
			$maxLetter = $tmp->returnMaxFrequenceLetter();
			if($maxLetter == $letter) {
				$position += 1;
				return($position);
				break;
			}
			else {
				$position += 1;
				$tmp->removeCoupleFromLetter($maxLetter);
			}
		}
		return($position);
	}
	
	function returnLetterByHigherFrequencePosition($varPosition) {
		$position = 0;
		$tmp = clone $this;
		$maxLetter = null;
		foreach ($tmp->couplesLetterFrequence as $value){
			$maxLetter = $tmp->returnMaxFrequenceLetter();
			if($position == $varPosition) {
				return($maxLetter);
			}
			else {
				$position += 1;
				$tmp->removeCoupleFromLetter($maxLetter);
			}
		}
		return($maxLetter);
	}
	
	function getSizeCouplesLetterFrequence() {
		return(count($this->couplesLetterFrequence));
	}
	
	function toString() {
		
		echo "<table>";
		echo "<tr>";
		echo "<th>Lettre</th>";
		echo "<th>Frequence</th>";
		echo "</tr>";
		
		foreach ($this->couplesLetterFrequence as $value){
			echo "<tr>";
			echo "<td>".$value['letter']."</td>";
			echo "<td>".$value['frequence']."</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
	
	function orderByFrequence() {

		$tmp = array();
		$n = $this->getSizeCouplesLetterFrequence();
		while($n != 0) {
			$maxLetter = $this->returnMaxFrequenceLetter();
			
			foreach ($this->couplesLetterFrequence as $value){
				if($value['letter'] == $maxLetter) {
					array_push($tmp, $value);
					$this->removeCoupleFromLetter($maxLetter);
					$n--;
					break;
				}
			}
		}
		$this->couplesLetterFrequence = $tmp;
	}
}

?>