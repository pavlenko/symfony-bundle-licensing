<?php

namespace PE\Bundle\LicensingBundle\Repository;

use PE\Bundle\LicensingBundle\Model\LicenseInterface;

interface LicenseRepositoryInterface
{
    /**
     * @param string $key
     *
     * @return LicenseInterface|null
     */
    public function findLicenseByKey($key);

    /**
     * @return LicenseInterface
     */
    public function createLicense();

    /**
     * @param LicenseInterface $license
     * @param bool             $flush
     */
    public function updateLicense(LicenseInterface $license, $flush = true);

    /**
     * @param LicenseInterface $license
     * @param bool             $flush
     */
    public function removeLicense(LicenseInterface $license, $flush = true);

    /**
     * Flush changes
     */
    public function flush();
}