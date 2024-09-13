# calDAV

### Diagrame des classes

Allez sur https://mermaid.live/ pour executer ce code ainsi voir le diagramme

classDiagram
    class PlateformInterface {
        <<interface>>
        +login()
        +getCalendar()
        +getCalendars()
        +createCalendar()
        +deleteCalendar()
        +...()
    }

    class Plateform {
        <<abstract>>
        +static plateformMap: array
        +srvUrl: string
        +static getInstance():self
        +abstract parseCredentials(string): Credentials
    }

    class Google {
    }

    class Office365 {
    }

    class Zimbra {
    }

    class Baikal {
    }

    class TokenCredentials {
        -token: string
        +setToken(string token): self
        +getToken(): string
        +__toString(): string
    }

    class ClassiCredentials {
        -username: string
        -password:string
        +__toString(): string
    }

    PlateformInterface <|.. Plateform
    Plateform <|-- Google
    Plateform <|-- Office365
    Plateform <|-- Zimbra
    Plateform <|-- Baikal
    Google o-- TokenCredentials
    Zimbra o-- TokenCredentials
    Baikal o-- ClassiCredentials
    Zimbra o-- ClassiCredentials
    Office365 o-- TokenCredentials

