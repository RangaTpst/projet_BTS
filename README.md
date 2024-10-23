# Projet SR Nails

Bienvenue dans le projet **SR Nails**, une application web dédiée aux services de manucure en ligne. Ce projet comprend une interface utilisateur pour les clients ainsi qu'un tableau de bord administrateur pour la gestion des produits et des utilisateurs.

## Table des matières
- [À propos](#à-propos)
- [Technologies](#technologies)
- [Installation](#installation)
- [Arborescence du projet](#arborescence-du-projet)
- [Fonctionnalités principales](#fonctionnalités-principales)
- [Contributions](#contributions)
- [Licence](#licence)

## À propos

SR Nails est une plateforme qui permet aux utilisateurs de consulter les produits de manucure, d'effectuer des commandes et de gérer leurs profils. Les administrateurs disposent d'un tableau de bord pour gérer les produits, les utilisateurs et les commandes.

## Technologies

- **PHP** : Langage de programmation serveur utilisé pour la logique métier et les interactions avec la base de données.
- **MySQL** : Base de données pour stocker les informations relatives aux utilisateurs, produits, commandes, etc.
- **HTML/CSS** : Utilisé pour structurer et styliser les pages web.
- **JavaScript** : Pour des interactions dynamiques côté client.
- **Git** : Système de contrôle de version pour le suivi des modifications.

## Installation

### Prérequis

- Serveur Apache avec PHP 7.4+ installé.
- Base de données MySQL.
- Composer (si vous avez des dépendances PHP à gérer).
- Git pour cloner le dépôt.

### Étapes d'installation

1. Clonez le dépôt Git :
   ```bash
   git clone https://github.com/votre-utilisateur/sr-nails.git

2. Naviguez dans le répertoire du projet :
   ```bash
   cd sr-nails

3. Configurez la base de données :
    Modifiez le fichier config/db.php pour correspondre aux informations de connexion de votre base de données locale (hôte, utilisateur, mot de passe, etc.).

4. Importez le fichier SQL de la base de données (le script SQL doit être fourni si nécessaire).

5. Lancez votre serveur local pour tester l'application.

## Arborescence du projet

Voici la structure du projet complète :

projet/
├── README.md
├── admin/
│   ├── add_product.php          # Formulaire pour ajouter un produit
│   ├── admin_dashboard.php      # Tableau de bord pour l'administrateur
│   ├── edit_product.php         # Modifier un produit existant
│   └── process_add_product.php  # Traitement d'ajout de produit
├── config/
│   └── db.php                   # Configuration de la base de données
├── core/
│   ├── cart-functions.php       # Fonctions liées au panier (vide pour le moment)
│   ├── product-functions.php    # Fonctions de gestion des produits
│   └── user_function.php        # Fonctions de gestion des utilisateurs (inscription, connexion, etc.)
├── includes/
│   ├── footer.php               # Pied de page commun
│   └── header.php               # En-tête commun
├── public/
│   ├── 404.php                  # Page d'erreur 404
│   ├── about.php                # Page "À propos"
│   ├── cart.php                 # Page du panier
│   ├── checkout.php             # Page de validation de commande
│   ├── contact.php              # Page de contact
│   ├── index.php                # Page d'accueil
│   ├── login.php                # Page de connexion
│   ├── logout.php               # Traitement de déconnexion
│   ├── privacy_policy.php       # Page de politique de confidentialité
│   ├── product.php              # Affichage des produits
│   ├── signup.php               # Page d'inscription
│   ├── user_dashboard.php       # Tableau de bord utilisateur
│   ├── assets/
│   │   ├── css/
│   │   │   ├── styles.css        # Styles généraux
│   │   │   ├── 404.css           # Styles spécifiques pour la page 404
│   │   │   ├── about.css         # Styles pour la page "À propos"
│   │   │   ├── admin.css         # Styles pour le tableau de bord admin
│   │   │   ├── contact.css       # Styles pour la page de contact
│   │   │   ├── dashboard.css     # Styles pour le tableau de bord utilisateur
│   │   │   ├── header.css        # Styles pour le header
│   │   │   ├── login.css         # Styles pour la page de connexion
│   │   │   └── policy.css        # Styles pour la politique de confidentialité
│   │   └── image/               # Images utilisées sur le site
│   ├── public/
│   │   └── image/               # Images spécifiques accessibles publiquement
│   └── uploads/                 # Fichiers téléchargés (produits, images d'utilisateurs, etc.)

## Fonctionnalités principales

- Connexion et inscription des utilisateurs
- Ajout et gestion de produits par les administrateurs
- Affichage et recherche de produits pour les utilisateurs
- Gestion du panier et validation des commandes
- Page de contact et à propos

## Contributions
Les contributions sont les bienvenues ! Si vous avez des idées d'amélioration ou de nouvelles fonctionnalités, n'hésitez pas à créer une pull request ou à ouvrir une issue.

## Licence
Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.