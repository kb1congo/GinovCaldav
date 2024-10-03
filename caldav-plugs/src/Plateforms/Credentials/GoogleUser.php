<?php

namespace Ginov\CaldavPlugs\Plateforms\Credentials;

use Ginov\CaldavPlugs\PlateformUserInterface;
use JsonSerializable;


class GoogleUser implements PlateformUserInterface, JsonSerializable
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

    public function jsonSerialize(): mixed
    {
        return [
            'token' => $this->token
        ];
    }
}