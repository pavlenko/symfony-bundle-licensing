<?php

namespace PE\Bundle\LicensingBundle\Server;

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\CryptoException;
use Defuse\Crypto\Key;
use GuzzleHttp\Psr7\Response;
use PE\Bundle\LicensingBundle\Exception\ServerException;
use PE\Bundle\LicensingBundle\Repository\LicenseRepositoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Server
{
    /**
     * @var string
     */
    private $serverKey;

    /**
     * @var LicenseRepositoryInterface
     */
    private $repository;

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ServerException
     */
    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $serverKey = Key::loadFromAsciiSafeString($this->serverKey);
            $clientKey = Crypto::decrypt((string) $request->getBody(), $serverKey);

            $license = $this->repository->findLicenseByKey($clientKey);
            if (!$license) {
                throw new ServerException('License not found', 404);
            }

            return new Response(200, [], Crypto::encrypt(serialize($license), $serverKey));
        } catch (CryptoException $ex) {
            throw new ServerException('Cannot handle request', 0, $ex);
        }
    }
}