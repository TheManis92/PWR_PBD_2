<?php


namespace DoctrineMigrations;


use App\Entity\Role;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20210122201605 extends AbstractMigration {

	public function up(Schema $schema): void {
		$roles = [
			Role::ROLE_USER				=>	'User',
			Role::ROLE_MODERATOR		=>	'Moderator',
			Role::ROLE_ADMINISTRATOR	=>	'Administrator'
		];

		foreach ($roles as $role => $roleName) {
			$values = "('{$role}', '{$roleName}')";
			$this->addSql('INSERT INTO role (role, name) VALUES ' . $values);
		}
	}
}