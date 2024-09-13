<?php

namespace Ginov\CaldavPlugs\Plateforms;

use App\Security\User;
use Ginov\CaldavPlugs\Dto\CalendarCalDAV;
use Ginov\CaldavPlugs\Dto\EventCalDAV;
use Ginov\CaldavPlugs\Plateform;
use Ginov\CaldavPlugs\PlateformUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BaikalUser implements PlateformUserInterface
{

    private string $username;
    private string $password;
    private string $calID;

    /**
     * Get the value of username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of calID
     *
     * @return string
     */
    public function getCalID(): string
    {
        return $this->calID;
    }

    /**
     * Set the value of calID
     *
     * @param string $calID
     * @return self
     */
    public function setCalID(string $calID): self
    {
        $this->calID = $calID;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username . ';' . $this->password . ';' . $this->calID;
    }
}

class Baikal extends Plateform
{

    /** @var SimpleCalDAVClient */
    private $client;

    public function __construct(ParameterBagInterface $parameter)
    {
        $this->srvUrl = $parameter->get('baikal.srv.url');
    }

    /**
     * Undocumented function
     *
     * @param PlateformUserInterface $userDto
     * @return PlateformUserInterface
     */
    public function kokokoo(Request $request): PlateformUserInterface
    {
        /**@var BaikalUser $user */
        $user = (new BaikalUser())
            ->setUsername($request->request->get('username'))
            ->setPassword($request->request->get('password'))
            ->setCalID($request->request->get('cal_name'));

        return $user;
    }

    public function calendar(string $credentials, string $calID): CalendarCalDAV
    {

        return new CalendarCalDAV($calID);
    }

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @return array
     */
    public function calendars(string $credentials): array
    {
        return [];
    }

    /**
     * Undocumented function
     *
     * @param string $password
     * @param string $username
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function getCalendars(string $password, string $username = 'baikal', int $offset = 0, int $limit = 20): array
    {

        $calendars = [];

        $client = $this->doConnect($username, $password);

        $results = $client->findCalendars();

        foreach ($results as $result) {
            $calendars[] = $result;
        }

        return $calendars;
    }

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @return array
     */
    public function events(string $credentials, string $calID): array
    {
        return [];
    }

    public function getEvents(string $idCal, string $password, string $username = 'baikal', int $dateStart = 0, int $dateEnd = 20): array
    {

        $client = $this->doConnect($username, $password);

        $calendars = $client->findCalendars();
        $client->setCalendar($calendars[$idCal]);

        $results = $client->getEvents(
            EventDto::formatDate(date('Y-m-d H:i:s', $dateStart)),
            EventDto::formatDate(date('Y-m-d H:i:s', $dateEnd))
        );

        $events = [];

        foreach ($results as $result) {
            $events[] = $result;
        }

        return $events;
    }

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param EventCalDAV $event
     * @return EventCalDAV     
     * */
    public function createEvent(string $credentials, EventCalDAV $event): EventCalDAV
    {
        return new EventCalDAV();
    }

    public function createCalendar(string $credentials, CalendarCalDAV $calendar): CalendarCalDAV
    {
        return new CalendarCalDAV('TODO');
    }

    public function deleteCalendar(string $credentials, string $calID) {}

    /**
     * Undocumented function
     *
     * @param string $username
     * @param string $password
     * @param string $calID
     * @param EventDto $event
     * @return string
     */
    public function addEvent(string $username, string $password, string $calID, EventDto $event): EventDto
    {
        $client = $this->doConnect($username, $password);

        $arrayOfCalendars = $client->findCalendars();
        $client->setCalendar($arrayOfCalendars[$calID]);

        $event = $client->create($event);

        return $event->getData();
    }


    private function _____doConnect(string $username, string $password)
    {
        if (!$this->client) {
            $this->client = new SimpleCalDAVClient();
            $this->client->connect($this->srvUrl, $username, $password);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $username
     * @param string $password
     * @return SimpleCalDAVClient
     */
    private function doConnect(string $username, string $password): SimpleCalDAVClient
    {
        $client = new SimpleCalDAVClient();
        $client->connect($this->srvUrl, $username, $password);
        return $client;
    }
}
