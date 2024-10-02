<?php

namespace Ginov\CaldavPlugs;

use Ginov\CaldavPlugs\Dto\CalendarCalDAV;
use Ginov\CaldavPlugs\Dto\EventCalDAV;
use Ginov\CaldavPlugs\PlateformUserInterface;
use Symfony\Component\HttpFoundation\Request;

interface PlateformInterface
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return PlateformUserInterface
     */
    public function login(Request $request): PlateformUserInterface;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param string $calID
     * @return CalDAV
     */
    public function getCalendar(string $credentials, string $calID): CalendarCalDAV;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @return array
     */
    public function getCalendars(string $credentials): array;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param CalendarCalDAV $calendar
     * @return CalendarCalDAV
     */
    public function createCalendar(string $credentials, CalendarCalDAV $calendar):CalendarCalDAV;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param string $calID
     * @return void
     */
    public function deleteCalendar(string $credentials, string $calID);

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param CalendarCalDAV $calendar
     * @return void
     */
    public function updateCalendar(string $credentials, CalendarCalDAV $calendar): CalendarCalDAV;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param string $calID
     * @return array
     */
    public function getEvents(string $credentials, string $calID, int $timeMin, int $timeMax): array;

    /**
     *  @param string $credentials
     *  @param string $calID
     *  @param string $eventID
     *  @return EventCalDAV
     */
    public function getEvent(string $credentials, string $eventID, string $calID): EventCalDAV;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param EventCalDAV $event
     * @return EventCalDAV
     */
    public function createEvent(string $credentials, string $calID, EventCalDAV $event): EventCalDAV;

    /**
     *  @param string $credentials
     *  @param string $calID
     *  @param string $eventID
     *  @return string
     */
    public function deleteEvent(string $credentials, string $calID, string $eventID): string;

    /**
     *  @param string $credentials
     *  @param string $calID
     *  @param string $eventID
     *  @param EventCalDAV $event
     *  @return EventCalDAV
     */
    public function updateEvent(string $credentials, string $calID, string $eventID, EventCalDAV $event): EventCalDAV;
}