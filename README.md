# calDAV

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
