<?php

namespace PE\Bundle\LicensingBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PELicensingBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        $this->addCompilerMappingsPass($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function addCompilerMappingsPass(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'PE\Component\Licensing\Model',
        ];

        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createYamlMappingDriver(
                $mappings,
                ['pe_licensing.model_manager_name'],
                'pe_licensing.backend_type.orm'
            ));
        }

        if (class_exists('Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass')) {
            $container->addCompilerPass(DoctrineMongoDBMappingsPass::createYamlMappingDriver(
                $mappings,
                ['pe_licensing.model_manager_name'],
                'pe_licensing.backend_type.mongodb'
            ));
        }
    }
}