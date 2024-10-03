<?php

namespace Ginov\CaldavPlugs\Plateforms\Credentials;

use Ginov\CaldavPlugs\PlateformUserInterface;
use JsonSerializable;



class BasicUser implements PlateformUserInterface, JsonSerializable
{
    private string $username;
    private string $password;


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
     *
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
     *
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function __toString(): string
    {
        return $this->username.';'.$this->password;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}