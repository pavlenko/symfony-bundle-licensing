<?php

namespace PE\Bundle\LicensingBundle\Client;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Key;
use GuzzleHttp\Client as HttpClient;
use PE\Bundle\LicensingBundle\Exception\ClientException;
use PE\Bundle\LicensingBundle\Model\LicenseInterface;
use Psr\Cache\CacheItemPoolInterface;

class Client
{
    const CACHE_KEY = '__PE_LICENSING_CLIENT__';

    /**
     * @var ClientConfig
     */
    private $config;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var HttpClient
     */
    private $http;

    /**
     * @param ClientConfig           $config
     * @param CacheItemPoolInterface $cache
     * @param HttpClient             $http
     */
    public function __construct(ClientConfig $config, CacheItemPoolInterface $cache, HttpClient $http)
    {
        $this->config = $config;
        $this->cache  = $cache;
        $this->http   = $http;
    }

    /**
     * Check license
     *
     * @throws ClientException
     */
    public function getLicense()
    {
        try {
            $cached = $this->cache->getItem(self::CACHE_KEY);

            if (!$cached->isHit()) {
                $serverKey = Key::loadFromAsciiSafeString($this->config->getServerKey());

                $response = $this->http->request('POST', $this->config->getServerURI(), [
                    'body' => Crypto::encrypt($this->config->getId(), $serverKey)
                ]);

                $license = unserialize(Crypto::decrypt((string) $response->getBody(), $serverKey));

                if (is_object($license) && !($license instanceof LicenseInterface)) {
                    throw new ClientException('Invalid data');
                }

                $cached
                    ->set($license)
                    ->expiresAfter($this->config->getServerTTL());
            }

            return $cached->get();
        } catch (\Psr\Cache\CacheException $ex) {
            throw new ClientException('Check failed', 0, $ex);
        } catch (\GuzzleHttp\Exception\GuzzleException $ex) {
            throw new ClientException('Check failed', 0, $ex);
        } catch (CryptoException $ex) {
            throw new ClientException('Check failed', 0, $ex);
        }
    }
}