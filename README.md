# FishingBuddy

Plateforme sociale pour les pêcheurs.

## Description

FishingBuddy est une application qui permet aux utilisateurs d'enregistrer un spot de pêche et de partager
ses prises.

### Fonctionnalités

#### Page d'accueil

Onboarding pour que les utilisateurs comprennent le fonctionnement et l'utilité du produit. Un "Hero" simple
et concis, deux sections décrivant les deux fonctionnalités actuellement disponibles. Un toast au message
personnalisable, et les réseaux sociaux.

#### Dashboard

- Affichage d'un widget comprenant la météo (température minimum, maximum et actuelle du jour), la date,
l'heure et l'emplacement de l'utilisateur (selon autorisation).
- Affichage d'un widget de suggestion de profils.
- Affichage d'un widget d'une activité aléatoire.

#### Map

- Carte interactive.
- Ajout d'un spot sur la carte (formulaire) :
    - Nom du spot
    - Permis lié
    - URL du permis
    - Espèces
    - Environnement
    - Règlementations
    - Équipements
    - Accès
- Itinéraire (via Google Maps).
- Icône déplaçable sur la carte.
- Suppression d'un spot sur la carte.
- Affichage des informations liées à un spot.
- Modification d'un spot sur la carte (formulaire).
- Différentes options de l'API (zoom, plein écran, position, globe projection, échelle).

#### Activités

Pages d'actualités et de profils accessibles par les invités.

- Filtres.
- Date de publication.
- Recherche d'utilisateurs.
- Formulaire de publication.
- Suppression d'une activité.
- Suggestions d'utilisateurs.
- Modification d'une activité.
- Suppression d'un commentaire.
- Compteur de vues d'une publication.
- Publications : Récentes, Suivi(e)s.
- Possibilité de liker une publication.
- Bouton pour revenir en haut de la page.
- Possibilité de commenter une publication.
- Chargement des activités supplémentaires par un bouton.
- Affichage des prises des utilisateurs (Espèce, Leurre, Taille, Poids, Record, Emplacement).

#### Profil utilisateur

- Date de début d'activité.
- Partager le profil d'un utilisateur.
- Nombres de : Followers, Suivi(e)s et Posts.
- Publications : Récentes, Anciennes, Populaires.
- Possibilité de Suivre/Ne plus suivre un utilisateur.
- Lien direct vers le formulaire de publication ou la modification du profil (profil personnel).

#### Paramètres

- Dark mode.
- Suppression du compte.
- Modification du pseudo.
- Modification du mot de passe.
- Modification de l'adresse email.
- Ajout/Update/Suppression d'un avatar.
- Confidentialité des activités (Public, Suivi(e)s ou Privées).

## TODO

- Partage d'un spot.
- Ajouter des photos aux spots.
- Fonctionnalité sur les espèces.
- QR code pour partager le profil.
- Confidentialité sur publication.
- Fonctionnalité de gestion des permis.
- Pouvoir changer le style de la carte.
- Système de gamification (Badges, etc).
- Implémenter des notifications (Ex : Followers, etc).
- Utilisation de l'API static-map de MapTiler pour lier un spot à une prise.
- Fonctionnalité HeatMap (Selon le nombre de spots enregistrés dans la même zone).
- Affichage de la localisation de l'utilisateur sur son profil (selon confidentialité).

## Conclusion

FishingBuddy est une plateforme sociale dédiée aux pêcheurs. Combinant cartographie interactive, partage
de prises et interactions communautaires. Favorisant la découverte de nouveaux spots, l'échange entre passionnés
et une expérience utilisateur personnalisée.
