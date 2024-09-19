<?php

namespace Ginov\CaldavPlugs\Dto;

class CalendarCalDAV
{
	private string $url;
	private string $displayname;
	private string $ctag;

	private string $calendar_id;

	// private string $rgba_color;
	private string $rbg_color;
	private int $order;

	private string $description;
	// private string $summary;
	private string $timeZone;

	function __construct(string $calendar_id, string $timeZone = 'Europe/Paris')
	{
		$this->calendar_id = $calendar_id;
		$this->displayname = $calendar_id;
		$this->description = $calendar_id;
		$this->timeZone = $timeZone;
		$this->url = md5(time());
		$this->ctag = '';
	}

	function __toString()
	{
		return ('(URL: ' . $this->url . '   Ctag: ' . $this->ctag . '   Displayname: ' . $this->displayname . ')' . "\n");
	}

	// Getters

	function getURL()
	{
		return $this->url;
	}

	function getDisplayName()
	{
		return $this->displayname;
	}

	function getCTag()
	{
		return $this->ctag;
	}

	function getCalendarID()
	{
		return $this->calendar_id;
	}

	function getRBGcolor()
	{
		return $this->rbg_color;
	}

	function getOrder():int
	{
		return $this->order;
	}


	// Setters

	function setURL($url):self
	{
		$this->url = $url;
		return $this;
	}

	function setDisplayName($displayname):self
	{
		$this->displayname = $displayname;
		return $this;
	}

	function setCtag($ctag):self
	{
		$this->ctag = $ctag;
		return $this;
	}

	function setCalendarID($calendar_id):self
	{
		$this->calendar_id = $calendar_id;
		return $this;
	}

	function setRBGcolor($rbg_color):self
	{
		$this->rbg_color = $rbg_color;
		return $this;
	}

	function setOrder($order):self
	{
		$this->order = $order;
		return $this;
	}

	public function setTimeZone(string $timeZone):self
	{
		$this->timeZone = $timeZone;
		return $this;
	}

	public function getTimeZone():string
	{
		return $this->timeZone;
	}

	public function getDescription():string
	{
		return $this->description;
	}

	public function setDescription(string $description):self
	{
		$this->description = $description ? $description : $this->displayname;
		return $this;
	}
}