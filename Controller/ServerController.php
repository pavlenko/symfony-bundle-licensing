<?php

namespace PE\Bundle\LicensingBundle\Controller;

use PE\Component\Licensing\Server\ServerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller
{
    /**
     * @var ServerInterface
     */
    private $server;

    /**
     * @param ServerInterface $server
     */
    public function __construct(ServerInterface $server)
    {
        $this->server = $server;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \PE\Component\Licensing\Exception\ServerException
     */
    public function __invoke(Request $request)
    {
        return new Response((string) $this->server->handleLicenseRequest($request->getContent()));
    }
}