<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612170524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position_state ADD position_id INT NOT NULL');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN position TO broker_id');
        $this->addSql('ALTER TABLE position_state ADD CONSTRAINT FK_2F533626DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2F533626DD842E46 ON position_state (position_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE position_state DROP CONSTRAINT FK_2F533626DD842E46');
        $this->addSql('DROP INDEX IDX_2F533626DD842E46');
        $this->addSql('ALTER TABLE position_state DROP position_id');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN broker_id TO "position"');
    }
}
