<?php

namespace Ginov\CaldavPlugs\Plateforms\Credentials;

use Ginov\CaldavPlugs\PlateformUserInterface;
use JsonSerializable;

class ZimbraUser implements PlateformUserInterface, JsonSerializable
{
    public function __toString(): string
    {
        return '';
    }

    public function jsonSerialize(): mixed
    {
        return[];
    }
}