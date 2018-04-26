<?php

namespace PE\Bundle\LicensingBundle\Client;

class ClientConfig
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $serverKey;

    /**
     * @var string
     */
    private $serverURI;

    /**
     * @var int
     */
    private $serverTTL;

    /**
     * @param string $id
     * @param string $serverKey
     * @param string $serverURI
     * @param int    $serverTTL
     */
    public function __construct(string $id, string $serverKey, string $serverURI, int $serverTTL)
    {
        $this->id        = $id;
        $this->serverKey = $serverKey;
        $this->serverURI = $serverURI;
        $this->serverTTL = $serverTTL;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getServerKey(): string
    {
        return $this->serverKey;
    }

    /**
     * @return string
     */
    public function getServerURI(): string
    {
        return $this->serverURI;
    }

    /**
     * @return int
     */
    public function getServerTTL(): int
    {
        return $this->serverTTL;
    }
}