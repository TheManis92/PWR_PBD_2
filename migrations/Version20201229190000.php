<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;

final class Version20201229190000 extends AbstractMigration {
	public function __construct(Connection $connection, LoggerInterface $logger) {
		parent::__construct($connection, $logger);

		// workaround for enums to work properly during migrations
		$this->connection
			->getSchemaManager()
			->getDatabasePlatform()
			->registerDoctrineTypeMapping('enum', Types::STRING);
	}

	public function up(Schema $schema): void {
		// required method
	}

}
