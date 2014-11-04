<?php

require('alphabet.class.php');
require('alphabetFrequence.class.php');
require('cryptedMessage.class.php');

if(file_exists("ciphertext2.bin")) { 
	$ficCryptedMessage1 = file_get_contents("ciphertext7.bin"); 
}

if(file_exists("ciphertext3.bin")) { 
	$ficCryptedMessage2 = file_get_contents("ciphertext1.bin"); 
}

$xorText = $ficCryptedMessage1 ^ $ficCryptedMessage2;
echo $xorText;
echo "<br/>";
//crib_dragging($xorText, 'A computer is a general purpose device that can be programmed to carry out a set of arithmetic or logical operations automatically. Since a sequence of operations can be readily changed, the computer can solve more than one kind of problem. ');

$texteDecode = Cryptage($xorText, 'A computer is a general purpose device that can be programmed to carry out a set of arithmetic or logical operations automatically. Since a sequence of operations can be readily changed, the computer can solve more than one kind of problem.
Conventionally, a computer consists of at least one processing element, typically a central processing unit (CPU), and some form of memory. The processing element carries out arithmetic and logic operations, and a sequencing and control unit can change the order of operations in response to stored information. Peripheral devices allow information to be retrieved from an external source, and the result of operations saved and retrieved.
In World War II, mechanical analog computers were used for specialized military applications. During this time the first electronic digital computers were developed. Originally they were the size of a large room, consuming as much power as several hundred modern personal computers (PCs).');
echo $texteDecode;

$fp = fopen('ficEcriture.txt', 'w');
fwrite($fp, $texteDecode);
fclose($fp);

function string_to_ascii($string)
{
$ascii = NULL;
 
for ($i = 0; $i < strlen($string); $i++)
{
$ascii .= ord($string[$i]);
}
 
return($ascii);
}

function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}
function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function Cryptage($MDP, $Clef){
						
	$LClef = strlen($Clef);
	$LMDP = strlen($MDP);
						
	if ($LClef < $LMDP){
				
		$Clef = str_pad($Clef, $LMDP, $Clef, STR_PAD_RIGHT);
	
	}
				
	elseif ($LClef > $LMDP){

		$diff = $LClef - $LMDP;
		$_Clef = substr($Clef, 0, -$diff);

	}
			
	return $MDP ^ $Clef; // La fonction envoie le texte crypté
			
}

function crib_dragging($chaine, $crib){
	for($i = 0; $i < strlen($chaine)-strlen($crib); $i++) {
		$mot = substr($chaine, $i, $i+strlen($crib));
		$decode = $mot ^ $crib;
		$decodeMaj = strtoupper ($decode);
		//if($decode == $decodeMaj) {
		echo $i. " | ".$decode."<br/>";
		//}
	}
}
