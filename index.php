<?php

require('alphabet.class.php');
require('alphabetFrequence.class.php');
require('cryptedMessage.class.php');

if(file_exists("english.ref")) { 
	$ficLanguage = file_get_contents("english.ref"); 
}
if(file_exists("ciphertext.txt")) { 
	$ficCryptedMessage = file_get_contents("ciphertext.txt"); 
}

echo "<h2>Frequence de son texte en anglais</h2>";
$alphabetFrequence = new AlphabetFrequence;
$alphabetFrequence->createAlphabetFrequenceFromText($ficLanguage);
$alphabetFrequence->orderByFrequence();
$alphabetFrequence->toString();
/*
echo "'".$alphabetFrequence->returnMaxFrequenceLetter()."'<br/>";
$alphabetFrequence->removeCoupleFromLetter(" ");
echo "'".$alphabetFrequence->returnMaxFrequenceLetter()."'";
$alphabetFrequence->removeCoupleFromLetter("E");
echo "'".$alphabetFrequence->returnMaxFrequenceLetter()."'";
*/

echo "<h2>Frequence de son texte code</h2>";
$cryptedMessage = new CryptedMessage($ficCryptedMessage);
$decryptText = $cryptedMessage->decryptWithAlphabetFrequence($alphabetFrequence);
echo "Indice de coincidence: ".$cryptedMessage->calculIndiceCoincidence()."<br/>";
echo $cryptedMessage->cryptedText."<p></p>";

//Transformations manuels
/*  CORRESPOND AUX REMPLACEMENTS SELON LES PLUS FORTES FREQUENCES
$cryptedMessage->addTransformationLetters("X", " ");
$cryptedMessage->addTransformationLetters("K", "E");
$cryptedMessage->addTransformationLetters("Y", "T");
$cryptedMessage->addTransformationLetters("F", "A");
$cryptedMessage->addTransformationLetters("S", "O");
$cryptedMessage->addTransformationLetters("L", "N");
$cryptedMessage->addTransformationLetters("T", "I");
$cryptedMessage->addTransformationLetters("G", "R");
$cryptedMessage->addTransformationLetters("Z", "S");
$cryptedMessage->addTransformationLetters("M", "C");
$cryptedMessage->addTransformationLetters("Q", "L");
$cryptedMessage->addTransformationLetters("R", "H");
$cryptedMessage->addTransformationLetters("J", "D");
$cryptedMessage->addTransformationLetters("U", "M");
$cryptedMessage->addTransformationLetters("D", "U");
$cryptedMessage->addTransformationLetters("E", "P");
$cryptedMessage->addTransformationLetters("I", "F");
$cryptedMessage->addTransformationLetters("V", "G");
$cryptedMessage->addTransformationLetters("N", "B");
$cryptedMessage->addTransformationLetters("O", "Y");
$cryptedMessage->addTransformationLetters("W", "W");
$cryptedMessage->addTransformationLetters(" ", "V");
$cryptedMessage->addTransformationLetters("H", "K");
$cryptedMessage->addTransformationLetters("B", "Q");
$cryptedMessage->addTransformationLetters("A", "Z");
$cryptedMessage->addTransformationLetters("P", "J");
$cryptedMessage->addTransformationLetters("C", "X");
*/

$cryptedMessage->addTransformationLetters("X", " ");
$cryptedMessage->addTransformationLetters("K", "E");
$cryptedMessage->addTransformationLetters("Y", "T");
$cryptedMessage->addTransformationLetters("F", "A");
$cryptedMessage->addTransformationLetters("S", "O");
$cryptedMessage->addTransformationLetters("L", "N");
$cryptedMessage->addTransformationLetters("T", "I");
$cryptedMessage->addTransformationLetters("G", "R");
$cryptedMessage->addTransformationLetters("Z", "S");
$cryptedMessage->addTransformationLetters("M", "C");
$cryptedMessage->addTransformationLetters("Q", "L");
$cryptedMessage->addTransformationLetters("R", "H");
$cryptedMessage->addTransformationLetters("J", "D");
$cryptedMessage->addTransformationLetters("U", "M");
$cryptedMessage->addTransformationLetters("D", "U");
$cryptedMessage->addTransformationLetters("E", "P");
$cryptedMessage->addTransformationLetters("I", "F");
$cryptedMessage->addTransformationLetters("V", "G");
$cryptedMessage->addTransformationLetters("N", "B");
$cryptedMessage->addTransformationLetters("O", "Y");
$cryptedMessage->addTransformationLetters("W", "W");
$cryptedMessage->addTransformationLetters(" ", "V");
$cryptedMessage->addTransformationLetters("H", "K");
$cryptedMessage->addTransformationLetters("B", "Q");
$cryptedMessage->addTransformationLetters("A", "Z");
$cryptedMessage->addTransformationLetters("P", "J");
$cryptedMessage->addTransformationLetters("C", "X");

$messageDecrypte = $cryptedMessage->decryptWithTransformations();
$decryptedMessage = new CryptedMessage($messageDecrypte);
echo "Indice de coincidence: ".$decryptedMessage->calculIndiceCoincidence()."<br/>";
echo $messageDecrypte;



?>