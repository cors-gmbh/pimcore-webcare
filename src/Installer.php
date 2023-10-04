<?php

declare(strict_types=1);

/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
 */

namespace CORS\Bundle\WebCareBundle;

use Doctrine\DBAL\Connection;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;

class Installer implements InstallerInterface
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function install(): void
    {
        $this->connection->executeQuery('CREATE TABLE cors_webcare_site (id INT AUTO_INCREMENT NOT NULL, siteId INT DEFAULT NULL, active TINYINT(1) NOT NULL, clientId VARCHAR(255) DEFAULT NULL, organizationId VARCHAR(255) DEFAULT NULL, websiteId VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8MB4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB');
    }

    public function uninstall(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if ($schemaManager->tablesExist(['cors_webcare_site'])) {
            $schemaManager->dropTable('cors_webcare_site');
        }
    }

    public function isInstalled(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        return $schemaManager->tablesExist(['cors_webcare_site']);
    }

    public function canBeInstalled(): bool
    {
        return !$this->isInstalled();
    }

    public function canBeUninstalled(): bool
    {
        return $this->isInstalled();
    }

    public function canBeUpdated()
    {
        return false;
    }

    public function update()
    {
    }

    public function needsReloadAfterInstall(): bool
    {
        return true;
    }

    public function getOutput(): BufferedOutput|NullOutput
    {
        return new NullOutput();
    }
}
