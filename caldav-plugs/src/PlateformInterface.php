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
    public function kokokoo(Request $request): PlateformUserInterface;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param string $calID
     * @return CalDAV
     */
    public function calendar(string $credentials, string $calID): CalendarCalDAV;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @return array
     */
    public function calendars(string $credentials): array;

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
     * @param string $idCal
     * @return array
     */
    public function events(string $credentials, string $idCal): array;

    /**
     * Undocumented function
     *
     * @param string $credentials
     * @param EventCalDAV $event
     * @return EventCalDAV
     */
    public function createEvent(string $credentials, EventCalDAV $event): EventCalDAV;
}
