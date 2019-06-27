<?php

declare(strict_types=1);

namespace LizardsAndPumpkins\Messaging\Queue\Amqp\Driver;

use LizardsAndPumpkins\Messaging\Queue\Amqp\Driver\AmqpLib\AmqpLibFactory;
use LizardsAndPumpkins\Messaging\Queue\Amqp\IntegrationTestMasterFactory;
use LizardsAndPumpkins\Util\Factory\MasterFactory;

class AmqpLibTest extends AmqpDriverTestIntegration
{
    /**
     * @return AmqpDriverFactory|IntegrationTestMasterFactory
     */
    final protected static function createMasterFactoryWithAmqpDriver()
    {
        return self::createTestMasterFactory(new AmqpLibFactory());
    }

    final protected static function getQueueName(): string
    {
        return 'test-amqp-lib';
    }

    final protected function closeConnection(MasterFactory $factory)
    {
        /** @var AmqpLibFactory $factory */
        $factory->getAMQPConnection()->close();

        $this->removeClosedConnectionFromProperty($factory);
    }

    private function removeClosedConnectionFromProperty(MasterFactory $factory): void
    {
        $reflectionFactory = new \ReflectionClass($factory);
        $methodsReflection = $reflectionFactory->getProperty('methods');
        $methodsReflection->setAccessible(true);
        /** @var AmqpLibFactory $amqpFactory */
        $amqpFactory = $methodsReflection->getValue($factory)['getAMQPConnection'];
        $amqpFactoryReflection = new \ReflectionClass($amqpFactory);
        $connection = $amqpFactoryReflection->getProperty('connection');
        $connection->setAccessible(true);
        $connection->setValue($amqpFactory, null);
    }
}
