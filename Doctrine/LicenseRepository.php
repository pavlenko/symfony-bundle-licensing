<?php

namespace PE\Bundle\LicensingBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use PE\Bundle\LicensingBundle\Model\LicenseInterface;
use PE\Bundle\LicensingBundle\Repository\LicenseRepositoryInterface;

class LicenseRepository extends AbstractRepository implements LicenseRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findLicenseByKey($key)
    {
        return $this->getRepository()->findOneBy(['key' => $key]);
    }

    /**
     * @inheritDoc
     */
    public function createLicense()
    {
        $class = $this->getClass();
        return new $class;
    }

    /**
     * @inheritDoc
     */
    public function updateLicense(LicenseInterface $license, $flush = true)
    {
        $manager = $this->getManager();
        $manager->persist($license);

        if ($flush) {
            $manager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function removeLicense(LicenseInterface $license, $flush = true)
    {
        $manager = $this->getManager();
        $manager->remove($license);

        if ($flush) {
            $manager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        $this->getManager()->flush();
    }
}