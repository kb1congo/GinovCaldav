<?php

namespace Ginov\CaldavPlugs\Plateforms\Credentials;

use Ginov\CaldavPlugs\PlateformUserInterface;


class GoogleUser implements PlateformUserInterface
{
    public function __toString(): string
    {
        return '';
    }
}