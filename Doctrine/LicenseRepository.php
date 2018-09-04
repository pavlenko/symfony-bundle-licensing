<?php

namespace PE\Bundle\LicensingBundle\Doctrine;

use PE\Component\Licensing\Model\LicenseInterface;
use PE\Component\Licensing\Repository\LicenseRepositoryInterface;

class LicenseRepository extends AbstractRepository implements LicenseRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findLicenses(): array
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findLicenseByKey(string $key): ?LicenseInterface
    {
        return $this->getRepository()->findOneBy(['key' => $key]);
    }

    /**
     * @inheritDoc
     */
    public function findLicenseByID(string $id): ?LicenseInterface
    {
        return $this->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function createLicense(): LicenseInterface
    {
        $class = $this->getClass();
        return new $class;
    }

    /**
     * @inheritDoc
     */
    public function updateLicense(LicenseInterface $license, $flush = true): void
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
    public function deleteLicense(LicenseInterface $license, $flush = true): void
    {
        $manager = $this->getManager();
        $manager->remove($license);

        if ($flush) {
            $manager->flush();
        }
    }
}