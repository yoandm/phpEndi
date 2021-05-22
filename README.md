# phpEndi

## Introduction

Cette bibliothèque permet de piloter la plateforme enDI dans le but de pouvoir automatiser la création de facture notamment.
Elle est largement perfectible et n'est qu'un premier jet pour l'instant.

Celle-ci est utilisable pour la création de factures en mode HT dans des affaires "classiques".

## Usage
Cette section sera complétée dans le futur.
En attendant il suffit de vous rendre dans le répertoire "examples".

Un point de détail tout de même pour la connexion.
La méthode "connect" dispose de 4 paramètres :
- email de connexion à enDI
- mot de passe de connexion
- L'URL où enDI est installé (https://endi.mondomaine.com par ex)
  Il faudra préciser le sous-répertoire si enDI n'est pas à la racine
- l'identifiant de votre structure
