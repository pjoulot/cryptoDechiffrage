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
$englishMessage = new CryptedMessage($ficLanguage);
$indiceCoincidenceAlphabetFrequence = $englishMessage->calculIndiceCoincidence(1);
echo "Indice de coincidence de l'anglais: ".$indiceCoincidenceAlphabetFrequence."<br/>";
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
$cryptedMessage->alphabetFrequence->toString();

//On décrypte avec les fréquences les plus présentes en monoalphabétique

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

echo "<h2>Message crypte</h2>";

echo $cryptedMessage->cryptedText."<p></p>";

echo "<h2>Dechiffrement monoalphabetique</h2>";

$messageDecrypte = $cryptedMessage->decryptWithTransformations();
$decryptedMessage = new CryptedMessage($messageDecrypte);
echo $messageDecrypte;
echo "<br/>";

echo "<h2>Calcul des indices de coincidences</h2>";

for($i=1; $i <21; $i++) {
	$indiceCoincidenceCrypted = $cryptedMessage->calculIndiceCoincidence($i);
	echo "Taille de cle = ".$i." | Indice de coincidence: ".$indiceCoincidenceCrypted."<br/>";
}

$cryptedMessage->decoupageTexte(16);
$textDecripteAvecDecoupage = $cryptedMessage->decryptWithDecoupage($alphabetFrequence);
echo "<h2>Texte decrypte via un decoupage de cle de taille 16 (puis analyse frequentielle)</h2>";
echo $textDecripteAvecDecoupage."<p></p>";
/*
$cryptedMessage->decoupageTexte(6);
$textDecripteAvecDecoupage = $cryptedMessage->decryptWithDecoupage($alphabetFrequence);
echo "<h2>Texte decrypte via un decoupage de cle de taille 6 (puis analyse frequentielle)</h2>";
echo $textDecripteAvecDecoupage."<p></p>";
*/

echo "<h2>Analyse de frequences des 16 morceaux</h2>";
$cryptedMessage->analizeFrequenceSubtexts();


/* Test pour Cesar */
$alphabetArray = array(" ", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
$alphabet = new Alphabet();
$alphabet->add_elements($alphabetArray);

//Trop long à exécuter
//$cryptedMessage->brutForceDecoupageCesar($alphabet);
echo "<h2>Utilisation d'une cle par etape en substituant grace aux etudes de frequences</h2>";
//echo $cryptedMessage->decryptDecoupageWithKey($alphabet, array(0,8,6,0,0,10,0,0,0,0,7,0,0,0,23,0));
//echo $cryptedMessage->decryptDecoupageWithKey($alphabet, array(0,8,6,0,2,10,0,22,0,16,7,16,0,3,23,0));
echo $cryptedMessage->decryptDecoupageWithKey($alphabet, array(13,8,6,23,2,10,9,22,20,21,7,21,3,8,23,21));






?>