<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202222010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add created_at column as nullable first
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // Set default value for existing users
        $this->addSql('UPDATE user SET created_at = NOW() WHERE created_at IS NULL');

        // Make the column NOT NULL after setting default values
        $this->addSql('ALTER TABLE user MODIFY created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');

        // Other migrations
        $this->addSql('ALTER TABLE post CHANGE text text VARCHAR(255) DEFAULT NULL, CHANGE image_name image_name VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE post CHANGE text text VARCHAR(255) DEFAULT \'NULL\', CHANGE image_name image_name VARCHAR(255) DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user DROP created_at, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE birthday birthday DATE DEFAULT \'NULL\', CHANGE profile_picture profile_picture VARCHAR(255) DEFAULT \'NULL\', CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
    }
}
