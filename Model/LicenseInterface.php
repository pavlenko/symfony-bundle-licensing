<?php

namespace PE\Bundle\LicensingBundle\Model;

interface LicenseInterface
{
    /**
     * @return string
     */
    public function getID();

    /**
     * @param string $id
     *
     * @return self
     */
    public function setID($id);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     *
     * @return self
     */
    public function setKey($key);
}