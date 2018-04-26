<?php

namespace PE\Bundle\LicensingBundle\Controller;

use PE\Component\Licensing\License;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends Controller
{
    public function checkAction()
    {
        $license = new License();

        $this->get('pe_licensing.client')->check($license);

        return new Response('', 200);
    }

    public function receiveAction(Request $request)
    {
        $license = $this->get('pe_licensing.receiver')->receive($request->getContent());

        $this->get('pe_licensing.writer')->write($license, '/path/to/file');

        return new Response('', 200);
    }
}