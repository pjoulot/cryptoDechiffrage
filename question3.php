<?php

require('alphabet.class.php');
require('alphabetFrequence.class.php');
require('cryptedMessage.class.php');

if(file_exists("ciphertext2.txt")) { 
	$ficCryptedMessage = file_get_contents("ciphertext2.txt"); 
}

$cryptedMessage = new CryptedMessage($ficCryptedMessage);

//echo "<h2>Message crypte</h2>";

//echo $cryptedMessage->cryptedText."<p></p>";

$cryptedMessage->decoupageBlocs(13);

//echo $cryptedMessage->sousTextesToString();

array_push($cryptedMessage->transformations, array(0, 10));
array_push($cryptedMessage->transformations, array(1, 6));
array_push($cryptedMessage->transformations, array(2, 4));
array_push($cryptedMessage->transformations, array(3, 1));
array_push($cryptedMessage->transformations, array(4, 3));
array_push($cryptedMessage->transformations, array(5, 7));
array_push($cryptedMessage->transformations, array(6, 0));
array_push($cryptedMessage->transformations, array(7, 9));
array_push($cryptedMessage->transformations, array(8, 5));
array_push($cryptedMessage->transformations, array(9, 11));
array_push($cryptedMessage->transformations, array(10, 8));
array_push($cryptedMessage->transformations, array(11, 2));
array_push($cryptedMessage->transformations, array(12, 12));

$cryptedMessage->applyTranslations();

echo "<h2>Texte en tableau</h2>";

echo $cryptedMessage->sousTextesToString();

//$cryptedMessage->defineColonnesImpossibles();

echo $cryptedMessage->toStringSubStringReunited();
