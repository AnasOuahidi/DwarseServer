#Dwarse Development
##Serveur du projet transversale
###Lancer en local : 
Cloner le projet
```
git clone https://github.com/AnasOuahidi/DwarseServer.git
```
Installer les dépendances
```
composer update
```
Lancer le serveur
```
php bin/console server:run
```
####Commandes utiles
Créer la base de données
```
php bin/console doctrine:database:create
```
Créer le schema (les tables) de la base de données
```
php bin/console doctrine:schema:create
```
Mettre à jour le schema (les tables) de la base de données
```
php bin/console doctrine:schema:update --force
```
Ajouter des données de test à la base de données
```
php bin/console doctrine:fixture:load
```
Supprimer la base de données
```
php bin/console doctrine:database:drop --force
```
####Equipe :<br />
Chef de Projet : [Yasser Gueddou](https://github.com/herfedos) <br />
Responsable Qualité : [Jenifer Gadomski](https://github.com/JeniferGadomski) <br />
Responsable Métier : [Anas Ouahidi](https://github.com/AnasOuahidi) <br />
Developpeur : [Mohamed Aziz Ben Miled](https://github.com/mabenmiled) <br />
Developpeur : [Nihel Ben Gamra](https://github.com/nbengamra) <br />
Developpeur : [Philippe Dezarnaud](https://github.com/Phi-l) <br />

With Love :heart: