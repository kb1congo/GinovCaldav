<?php

namespace Ginov\CaldavPlugs\Plateforms\Credentials;

use Ginov\CaldavPlugs\PlateformInterface;
use Ginov\CaldavPlugs\PlateformUserInterface;

class OutlookUser implements PlateformUserInterface
{

    private string $token;

    private string $username;

    private string $email;

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function __toString(): string
    {
        return $this->token . ';' . $this->username . ';' . $this->email;
    }
}