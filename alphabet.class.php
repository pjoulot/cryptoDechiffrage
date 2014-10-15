<?php

class Alphabet {

	var $elements = array();
	
	function add_element($lettre) {
        array_push($this->elements, $lettre);
    }
	
	function add_elements($lettres) {
        foreach ($lettres as $value){
			$this->add_element($value);
		}
    }
	
	function toString() {
		$i =0;
		echo "Alphabet: ";
		foreach ($this->elements as $value){
			if($i != 0)
				echo ", '".$value."'";
			else 
				echo "'".$value."'";
			$i++;
		}
	}
}

?>