# calDAV

### 1. **Introduction**
   - **Description générale** : Présente un résumé de la librairie, son objectif, et les cas d'utilisation principaux.
   - **Fonctionnalités principales** : Liste des fonctionnalités offertes (ex: gestion des calendriers, événements, participants, etc.).
   - **Technologies** : Dépendances et versions minimales requises (PHP, extensions nécessaires, etc.).
   - **Installation rapide** : Commandes pour installer la librairie via Composer, ou autres méthodes d’installation.

   **Exemple :**
   ```bash
   composer require mycompany/caldav-lib
   ```

### 2. **Prérequis**
   - **Environnement requis** : Versions PHP, modules PHP requis (comme `curl`, `xml`, etc.).
   - **Autres dépendances** : Frameworks ou bibliothèques complémentaires nécessaires.
   
### 3. **Guide d'installation**
   - **Installation via Composer**
   - **Configuration des dépendances** : Si la librairie a besoin de configurations supplémentaires.
   - **Exemple de configuration** : Fichier de configuration type à placer dans un projet.
   
### 4. **Démarrage rapide**
   - **Premier exemple** : Exemple basique de création d’un calendrier ou d’un événement pour que l’utilisateur se familiarise rapidement avec la librairie.
   - **Exemple de code simple** :
     ```php
     use MyCompany\CalDAV\Client;
     
     $client = new Client($baseUri, $username, $password);
     $client->createCalendar($calendarName);
     ```
   - **Autres cas d'utilisation rapide** : Ajout d'un événement, récupération d'un calendrier, suppression, etc.

### 5. **Architecture de la librairie**
   - **Classes principales** : Liste des classes principales (Client, Calendar, Event, etc.) et leur rôle.
   - **Diagramme UML simplifié** (si nécessaire) pour illustrer les relations entre les composants de la librairie.

### 6. **Utilisation détaillée**
   - **Authentification** : Description des différentes méthodes d’authentification supportées.
   - **Gestion des calendriers** :
     - Créer un calendrier.
     - Modifier un calendrier.
     - Supprimer un calendrier.
   - **Gestion des événements** :
     - Créer, récupérer, modifier, et supprimer des événements (VEVENT).
     - Gestion des participants (attendees).
   - **Exemples de requêtes HTTP** : Utilisation des verbes HTTP (GET, POST, PUT, DELETE) avec des exemples concrets.
   - **Gestion des erreurs** : Comment la librairie gère-t-elle les exceptions et erreurs ? Ajoute des exemples de code pour capturer les erreurs.

### 7. **Référence API**
   - **Classes et Méthodes** : Documentation complète des classes, méthodes, et propriétés publiques.
   - **Exemples de chaque méthode** : Pour chaque méthode importante, fournir un exemple d’utilisation.
   - **Typage des paramètres et retours** : Indique clairement le type des arguments et ce que la méthode retourne.

### 8. **FAQ**
   - Réponses aux questions fréquentes sur l’utilisation de la librairie, les erreurs courantes, ou les cas particuliers (problèmes de configuration serveur, etc.).

### 9. **Bonnes pratiques**
   - Conseils pour une utilisation optimale de la librairie (ex: optimisation des performances, gestion des erreurs, gestion des sessions, etc.).
   
### 10. **Contributions et évolutions**
   - **Guide de contribution** : Comment contribuer au projet (pull requests, issues).
   - **Changelog** : Historique des versions et des modifications majeures.
   - **Roadmap** : Fonctionnalités futures prévues.

### 11. **Support**
   - **Contact** : Comment obtenir de l'aide ou signaler des bugs.
   - **Communauté** : Forum, mailing list, ou autres canaux de communication (si disponibles).


### Diagrame des classes

Allez sur https://mermaid.live/ pour executer ce code ainsi voir le diagramme
```
classDiagram
    class PlateformInterface {
        <<interface>>
        +login(CredentialsInterface credentials) string
        +getCalendar(string credentails) CalendarCalDAV
        +getCalendars(string credentails, int limit, int offset = 0) List~EventCalDAV~
        +createCalendar(string credentails, CalendarCalDAV c) CalendarCalDAV
        +deleteCalendar(string credentails, string calendar_id) void
        +updateCalendar(string credentails, string calendar_id, List~string~ data) CalendarCalDAV
        +getEvent(string credentails) EventCalDAV
        +getEvents(string credentails, int limit, int offset = 0) List~CalendarCalDAV~
        +createEvent(string credentails, EventCalDAV e) EventCalDAV
        +delteEvent(string credentails, string event_id) void
        +updateEvent(string credentails, string event_id, List~string~ data) EventCalDAV
    }
    class OAuthUrl {
        <<interface>>
        + getOAuthUrl():string
    }
    class Factory {
        <<abstract>>
        -List~string~ plateformMap$
        +getInstance(ParameterBagInterface parameters) self$
    }
namespace Plateforms {
    class Google {
        +__construct(ParameterBagInterface parameters)
        +string srvUrl
        +string davSrvUrl
    }
    class Office365 {
        +__construct(ParameterBagInterface parameters)
        +string srvUrl
    }
    class Zimbra {
        +__construct(ParameterBagInterface parameters)
        +string davSrvUrl
    }
    class Baikal {
        +string davSrvUrl
    }
}
namespace Dto {
    class CalendarCalDAV {
        -string calendar_id
        -string displayName
        -string description
        -string timeZone
    }
    class EventCalDAV {
    }
}
    class TokenCredentials {
        -string token
        +setToken(string token) self
        +getToken() string
    }
    class BasiCredentials {
        -string username
        -string password
        +setUsername(string username) self
        +getUsername() string
        +setPassword(string password) self
        +getPassword() string
    }
    class CredentialsInterface {
        +parseCredentials(string) Credentials
        +__toString() string
    }
    PlateformInterface <|.. Factory
    Factory <|-- Google
    Factory <|-- Office365
    Factory <|-- Zimbra
    Factory <|-- Baikal
    CredentialsInterface<|.. TokenCredentials
    CredentialsInterface <|.. BasiCredentials
    Google o-- TokenCredentials
    Zimbra o-- TokenCredentials
    Baikal o-- BasiCredentials
    Zimbra o-- BasiCredentials
    Office365 o-- TokenCredentials
    %%OAuthUrl <|-- Google
    %%OAuthUrl <|-- Office365
    %%OAuthUrl <|-- Zimbra
    %%Google o-- CalendarCalDAV
    %%Zimbra o-- CalendarCalDAV
    %%Baikal o-- CalendarCalDAV
    %%Office365 o-- CalendarCalDAV
    %%Google o-- EventCalDAV
    %%Zimbra o-- EventCalDAV
    %%Baikal o-- EventCalDAV
    %%Office365 o-- EventCalDAV

```
