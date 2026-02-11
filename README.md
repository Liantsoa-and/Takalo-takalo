# Takalo-takalo
On veut mettre en place un site qui permet de faire des échanges d’objet : Takalo-takalo. Les utilisateurs inscrits sur le site vont mettre en ligne leurs objets (vêtement, livre, DVD, etc…). Ils vont voir les objets des autres utilisateurs et proposer un échange entre 2 objets. Si l’autre utilisateur accepte, l’objet change de propriétaire 

# **TODO List - Projet Takalo-takalo**

---

## **Page Admin – Login**
- **Base**
    - [ ] Créer table `admins` (id, email, password)
    - [ ] Pré-remplir formulaire avec identifiants par défaut
    - [ ] Vérification des identifiants en base
- **Design**
    - [ ] Formulaire simple avec Bootstrap (email + mot de passe)
    - [ ] Message d’erreur/succès stylisé
- **Intégration**
    - [ ] Lier le formulaire à une route Flight (`POST /admin/login`)
    - [ ] Redirection vers dashboard admin après connexion
- **Fonction**
    - [ ] Session admin sécurisée
    - [ ] Déconnexion possible

---

## **Page Admin – Gestion Catégories**
- **Base**
    - [ ] Table `categories` (id, nom, description)
    - [ ] CRUD complet (Create, Read, Update, Delete)
- **Design**
    - [ ] Tableau Bootstrap listant les catégories
    - [ ] Boutons Ajouter/Modifier/Supprimer
    - [ ] Modal pour ajout/édition
- **Intégration**
    - [ ] Route Flight pour chaque action CRUD
    - [ ] Vérifier que seul l’admin y accède
- **Fonction**
    - [ ] Validation des champs (nom obligatoire)
    - [ ] Impossible de supprimer une catégorie utilisée ou modifier tous les produits de cette categorie en cette nouvelle categorie.

---

## **Page Admin – Statistiques**
- **Base**
    - [ ] Requêtes SQL pour :
        - Nombre d’utilisateurs inscrits
        - Nombre d’échanges réalisés
- **Design**
    - [ ] Cartes Bootstrap (`cards`) avec icônes et chiffres
    - [ ] Graphique simple (optionnel) avec Chart.js
- **Intégration**
    - [ ] Route `/admin/stats` qui récupère les données
    - [ ] Afficher données dans vue
- **Fonction**
    - [ ] Données en temps réel
    - [ ] Export CSV optionnel

---

## **Page Utilisateur – Inscription / Login**
- **Base**
    - [ ] Table `users` (id, nom, email, password, date_inscription)
    - [ ] Hash des mots de passe (password_hash)
- **Design**
    - [ ] Formulaire Bootstrap avec validation frontale
    - [ ] Lien entre inscription et connexion
- **Intégration**
    - [ ] Routes Flight : `POST /register`, `POST /login`
    - [ ] Redirection vers profil après connexion
- **Fonction**
    - [ ] Vérifier email unique
    - [ ] Message de bienvenue après inscription

---

## **Page Utilisateur – Gestion Objets**
- **Base**
    - [x] Table `objects` (id, libelle, description, category_id, prix_estimatif, user_id)
    - [x] Table `photos` (id, object_id, url)
    - [x] CRUD objets (avec photos multiples)
- **Design**
    - [x] Formulaire avec upload d’images (multiple)
    - [x] Galerie des objets de l’utilisateur 
- **Intégration**
    - [wip] Routes Flight pour CRUD objets (delete ne marche pas encore)
    - [x] Upload des images dans `uploads/`
- **Fonction**
    - [x] Validation : titre, prix numérique
    - [x] Prévisualisation des images avant envoi

---

## **Page Utilisateur – Liste Objets Autres Utilisateurs**
- **Base**
    - [x] Requête SELECT avec jointure `users`, `categories`, `photos`
    - [x] Exclusion des objets de l’utilisateur connecté
- **Design**
    - [x] Grille de cartes Bootstrap (image + titre + catégorie)
    - [x] Bouton "Voir détail" sur chaque carte
- **Intégration**
    - [x] Route `/objets_publics` avec pagination
    - [x] Lien vers fiche objet
- **Fonction**
    - [x] Filtre par catégorie via liste déroulante

---

## **Page Utilisateur – Fiche Objet + Proposition Échange**
- **Base**
    - [x] Table `tt_echanges` (id, object1_id, object2_id, date_echange, statut_id)
    - [x] Table `tt_status` (id, libelle)
    - [x] Vérifier que l’objet proposé appartient à l’utilisateur connecté
- **Design**
    - [x] Afficher photos, description, propriétaire
    - [x] Liste déroulante des objets de l’utilisateur pour proposition
- **Intégration**
    - [x] Route `/objet/{id}` pour affichage
    - [x] Route `POST /echange/propose` pour créer une proposition
- **Fonction**
    - [x] Ne pas permettre de proposer son propre objet
    - [ ] Notification au propriétaire de l’objet

---

## **Page Utilisateur – Gestion des Échanges**
- **Base**
    - [ ] Requêtes pour lister :
        - Propositions reçues
        - Propositions envoyées
- **Design**
    - [ ] Onglets Bootstrap : "Reçues" / "Envoyées"
    - [ ] Boutons Accepter / Refuser
- **Intégration**
    - [ ] Route `/exchanges` avec filtres par statut
    - [ ] Actions POST pour accepter/refuser
- **Fonction**
    - [ ] Changement de propriétaire si accepté
    - [ ] Historique d’échange mis à jour

---

## **Page Utilisateur – Barre de Recherche**
- **Base**
    - [ ] Requête SQL avec `LIKE` sur `titre` et jointure `categories`
- **Design**
    - [ ] Champ recherche + bouton dans le header
    - [ ] Résultats sous forme de liste ou grille
- **Intégration**
    - [ ] Route `GET /search?q=...`
    - [ ] Recherche via AJAX (optionnel)
- **Fonction**
    - [ ] Recherche insensible à la casse
    - [ ] Message "Aucun résultat" si vide

---

## **Page Utilisateur – Historique d’Appartenance**
- **Base**
    - [ ] Table `ownership_history` (id, object_id, user_id, exchange_id, date)
    - [ ] Remplir automatiquement à chaque échange validé
- **Design**
    - [ ] Timeline verticale Bootstrap
    - [ ] Affichage date + propriétaire + photo de profil
- **Intégration**
    - [ ] Route `/object/{id}/history`
    - [ ] Accessible sans connexion (public)
- **Fonction**
    - [ ] Ordonner par date décroissante
    - [ ] Lien vers profil des anciens propriétaires

---

**Note générale :**
- [ ] Footer avec nom + numéro ETU des membres
- [ ] Git public avec README et liste des tâches (Trello/Notion lien)
- [ ] Données de test (fixtures SQL)
- [ ] Utilisation de FlightMvc
- [ ] Template Bootstrap ou personnalisé

