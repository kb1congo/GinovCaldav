<?php

namespace Ginov\CaldavPlugs\Plateforms;

use App\HttpTools;
use Sabre\VObject\Reader;
use Ginov\CaldavPlugs\Http;
use Ginov\CaldavPlugs\Factory;
use Ginov\CaldavPlugs\Dto\EventCalDAV;
use Ginov\CaldavPlugs\Dto\CalendarCalDAV;
use Ginov\CaldavPlugs\Plateform;
use Ginov\CaldavPlugs\PlateformUserInterface;
use Symfony\Component\HttpFoundation\sendHttpRequest;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GoogleUser implements PlateformUserInterface
{

    private string $token;

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}

class Google extends Factory
{
    private string $calDAVUrl;
    private string $certPath;
    /* private string $scope;
    private string $redirect_uri;
    private $client_id; */

    public function __construct(private ParameterBagInterface $parameters)
    {
        $this->srvUrl = $parameters->get('google.srv.url');
        $this->calDAVUrl = $parameters->get('google.caldav.url');
        // string $scope, string $redirect_uri, $client_id
    }

    public function getOAuthUrl(): string
    {
        return "https://accounts.google.com/o/oauth2/v2/auth?scope=" .
            $this->parameters->get('google.scope') . "&access_type=offline&include_granted_scopes=true&response_type=code&redirect_uri=" .
            $this->parameters->get('google.redirect.uri') . "&client_id=" .
            $this->parameters->get('google.client.id');
    }

    public function login(PlateformUserInterface $user): PlateformUserInterface
    {
        /** @var GoogleUser $user */
        $user = $user;

        dd($user);

        return $user;
    }

    public function kokokoo(Request $request): PlateformUserInterface
    {
        /** @var GoogleUser $user */
        $user = (new GoogleUser())
            ->setToken($request->request->get('token'));

        return $user;
    }

    public function calendar(string $credentials, string $calID): CalendarCalDAV
    {
        $response = (new Http($this->srvUrl))
            ->http()
            ->sendHttpRequest(
                'GET',
                "calendars/$calID",
                ['Authorization' => 'Bearer ' . $credentials]
            );

        if ($response->getStatus() != Response::HTTP_OK)
            throw new \Exception($response->getBodyAsString(), $response->getStatus());

        $json = json_decode($response->getBodyAsString(), true);

        return (new CalendarCalDAV($calID))
            ->setCtag($json['etag'])
            ->setDisplayName($json['summary'])
            ->setDescription($json['summary'])
            ->setTimeZone($json['timeZone']);
    }

    public function calendars(string $credentials): array
    {
        $response = (new Http($this->srvUrl))
            ->http()
            ->sendHttpRequest(
                'GET',
                'users/me/calendarList',
                ['Authorization' => 'Bearer ' . $credentials]
            );

        if ($response->getStatus() != Response::HTTP_OK)
            throw new \Exception($response->getBodyAsString(), $response->getStatus());


        $json = json_decode($response->getBodyAsString(), true);

        $items = [];
        foreach ($json['items'] as $value) {
            $items[] = (new CalendarCalDAV($value['id']))
                ->setCtag($value['etag'])
                ->setDisplayName($value['summary'])
                ->setDescription($value['summary'])
                ->setTimeZone($value['timeZone'])
                ->setRBGcolor($value['backgroundColor']);
        }

        return array(
            'next' => $json['nextSyncToken'],
            'items' => $items
        );
    }

    public function createCalendar(string $credentials, CalendarCalDAV $calendar): CalendarCalDAV
    {
        $response = (new Http($this->srvUrl))
            ->http()
            ->sendHttpRequest(
                'POST',
                'calendars',
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $credentials],
                (json_encode([
                    'summary' => $calendar->getCalendarID(),
                    'timeZone' => $calendar->getTimeZone(),
                    'description' => $calendar->getDescription()
                ]))
            );

        if ($response->getStatus() != Response::HTTP_OK)
            throw new \Exception($response->getBodyAsString(), $response->getStatus());

        /** @var array */
        $json = json_decode($response->getBodyAsString(), true);

        return (new CalendarCalDAV($json['id']))
            ->setCtag($json['etag'])
            ->setDisplayName($json['summary'])
            ->setDescription($json['description'])
            ->setTimeZone($json['timeZone']);
    }

    public function updateCalendar(string $credentials, CalendarCalDAV $calendar): CalendarCalDAV
    {
        return new CalendarCalDAV($calendar->getCalendarID());
    }

    public function deleteCalendar(string $credentials, string $calID)
    {
        $response = (new Http($this->srvUrl))
            ->http()
            ->sendHttpRequest('DELETE', 'calendars');

        if ($response->getStatus() != Response::HTTP_OK)
            throw new \Exception($response->getBodyAsString(), $response->getStatus());

        /** @var array */
        $json = json_decode($response->getBodyAsString(), true);

        return  $json;
    }

    public function events(string $credentials, string $idCal): array
    {
        $events = (new HttpTools($this->calDAVUrl, $this->certPath))
            ->get("$idCal/events", [], [
                "Content-Type" => "application/json",
                'Authorization' => "Bearer " . $credentials
            ])
            ->brut()
            ->getBody();

        return $this->parse((string)$events);
    }


    public function createEvent(string $credentials, string $calID, EventCalDAV $event): EventCalDAV
    {
        return new EventCalDAV();
    }

    private static function parse($icalendarData): array
    {
        $vcalendar = Reader::read($icalendarData);

        $results = [];

        foreach ($vcalendar->VEVENT as $event) {

            $results[] = (new EventCalDAV())
                ->setSummary($event->SUMMARY)
                ->setDescription($event->DESCRIPTION)
                ->setLocation($event->LOCATION)
                ->setDateStart($event->DTSTART)
                ->setDateEnd($event->DTEND)
                ->setTimeZoneID($vcalendar->VTIMEZONE->TZID)
                ->setRrule($event->RRULE)
                ->setUid($event->UID);
        }

        return $results;
    }

    private static function parseCredentials(string $credentials): PlateformUserInterface
    {
        $tmp = explode(';', $credentials);

        return (new GoogleUser())
            ->setToken($tmp[1]);
    }
}
