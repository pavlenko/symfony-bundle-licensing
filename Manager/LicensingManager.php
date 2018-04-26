<?php

namespace PE\Bundle\LicensingBundle\Manager;

use PE\Bundle\LicensingBundle\Repository\LicenseRepositoryInterface;

class LicensingManager
{
    /**
     * @var LicenseRepositoryInterface
     */
    private $licenseRepository;

    /**
     * @param LicenseRepositoryInterface $licenseRepository
     */
    public function __construct(LicenseRepositoryInterface $licenseRepository)
    {
        $this->licenseRepository = $licenseRepository;
    }

    public function flush()
    {
        $this->licenseRepository->flush();
    }
}