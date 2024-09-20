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
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OutLook extends Factory
{
    private $httpClient;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $scope;

    public function __construct(HttpClientInterface $httpClient, string $clientId, string $clientSecret, string $redirectUri, string $scope)
    {
        $this->httpClient = $httpClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->scope = $scope;
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
            ->sendHttpRequest('GET', "https://graph.microsoft.com/v1.0/me/calendars/$calID", [
                'Authorization' => 'Bearer ' . $credentials
            ])
            ->getBodyAsString();

        $json = json_decode($response, true);
        // dd($json);
        return (new CalendarCalDAV($calID))
            ->setCalendarID($json['id'])
            ->setDisplayName($json['name'])
            ->setDescription($json['name'])
            ->setTimeZone($json['timeZone']);
    }

    public function calendars(string $credentials): array
    {

        $response = (new Http($this->srvUrl))
            ->sendHttpRequest('GET', 'users/me/calendarList', [
                'Authorization' => 'Bearer ' . $credentials
            ])
            ->getBodyAsString();

        $items = [];
        $json = json_decode($response, true);
        // dd($json['items']);

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
            ->sendHttpRequest(
                'POST',
                'calendars',
                ['Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $credentials],
                (json_encode([
                    'summary' => $calendar->getCalendarID(),
                    'timeZone' => $calendar->getTimeZone(),
                    'description' => $calendar->getDescription()
                ]))
            )
            ->getBodyAsString();

        /** @var array */
        $json = json_decode($response, true);

        return (new CalendarCalDAV($json['id']))
            ->setCtag($json['etag'])
            ->setDisplayName($json['summary'])
            ->setDescription($json['summary'])
            ->setTimeZone($json['timeZone'])
            ->setRBGcolor($json['backgroundColor']);
    }

    public function deleteCalendar(string $credentials, string $calID): void
    {
        $response = (new Http($this->srvUrl))
            ->sendHttpRequest('DELETE', 'calendars');
    }

    public function events(string $credentials, string $idCal): array
    {
        // $events = (new HttpTools($this->calDAVUrl, $this->certPath))
        //     ->get("$idCal/events", [], [
        //         "Content-Type" => "application/json",
        //         'Authorization' => "Bearer " . $credentials
        //     ])
        //     ->brut()
        //     ->getBody();
        $events="Top";

        return $this->parse((string)$events);
    }
    public function updateCalendar(string $credentials, CalendarCalDAV $calendar): CalendarCalDAV
    {
        return new CalendarCalDAV($calendar->getCalendarID());
    }

    // public function createEvent(string $credentials, EventCalDAV $event): EventCalDAV
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
