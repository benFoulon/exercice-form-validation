<?php

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension DebugExtension
$twig->addExtension(new DebugExtension());


// traitement des donées
dump($_POST);
$formData = [
    'email' => '',
    'subject' => '',
    'comment' => ''
];

$errors = [];
$validation=[
    'email' =>'',
    'subject' => '',
    'comment' => '',
];

if ($_POST){

    foreach($formData as $key => $value){
        if (isset($_POST[$key])){
        $formData[$key] = $_POST[$key];
        }
    }

    if (empty($_POST['email'])) {
        $errors['email'] = "Merci de renseigner un email";
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false ){
        $errors['email'] = "merci de renseigner un email valide"; 
    } elseif (strlen($_POST['email']) > 190){
        $errors['email'] = "Votre email doit être composé de moins de 190 caractères.";
    } elseif ($errors['email']){
        $validation['email'] = 'is-invalid';
    }

    if (empty($_POST['subject'])){
        $errors['subject'] = "Merci de renseigner un objet";
    } elseif (strlen($_POST['subject']) < 3 || strlen($_POST['subject']) > 190 ) {
        $errors['subject'] = "Merci de renseigner un objet entre 3 et 190 caractères";
    } elseif ($errors['subject']){
        $validation['subject'] = 'is-invalid';
    }

    if (empty($_POST['comment'])){
        $errors['comment'] = "Merci d'entrer votre message";
    } elseif (strlen($_POST['comment']) < 3 || strlen($_POST['comment']) > 1000) {
        $errors['comment'] = "Merci d'entrer un message entre 3 et 1000 caractère maximum";
    } elseif(preg_match( '/<[^>]*>/', $_POST['comment']) !== 0){
        $errors['comment'] = "Merci d'entrer un message sans les caractère '<' et '>'";
    } elseif ($errors['comment']){
        $validation['comment'] = 'is-invalid';
    }
}





// affichage du rendu d'un template
echo $twig->render('contact.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'formData' => $formData,
    'validation'=> $validation
]);