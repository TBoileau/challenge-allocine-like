# Twitch - Défi N°1

## Objectifs 
* Concevoir un site Allocine's Like communautaire
* Rester en vie à la fin des 2 heures

## Règles
* Vous démarrez avec 30 points de vie
* Vous avez 2 heures pour concevoir le projet
* Vous utiliserez Symfony 6 et PHP8.0
* Interdiction d'utiliser le composant **Maker**
* Interdiction d'ouvrir votre navigateur
* Il faut se baser sur les tests fonctionnels pour concevoir vos pages
* PHPStan doit être configuré au niveau max (9)

## Résultats
* Chaque fonctionnalité non implémenté : **-5 points**
* Chaque erreur PHPStan : **-1 points**
* Chaque test qui échoue : **-3 points**
* Si vous dépassez le temps imparti : **-2 points par tranche de 10 minutes**
* Si les viewers estiment que le site est trop moche : **-5 points**

## Fonctionnalités à concevoir 
* Inscription
* Connexion
* Créer une fiche de film
* Modifier une fiche de film
* Supprimer une fiche de film
* Listing des films avec pagination (10 films par page)
* Poster une critique
* Page profile d'un utilisateur qui liste ses films et critiques
* Vous êtes responsable de créer les fixtures

## Règles métiers
* Un utilisateur possède
  * Une adresse email
  * Un pseudo
  * Un mot de passe

* Un film possède
  * Un titre
  * Une image
  * Une synopsis
  * Une durée
  * Année de sortie
  * Un.e ou plusieurs acteurs.rices
  * Un.e ou plusieurs réalisateurs.rices
  * Plusieurs critiques

* Une critique possède
  * Un titre
  * Une note de 1 à 10
  * Un contenu
  
* Une personne possède
  * Nom
  * Prénom
  * Nationalité

* Seul le créateur d'une fiche de film peut modifier ou supprimer le film
