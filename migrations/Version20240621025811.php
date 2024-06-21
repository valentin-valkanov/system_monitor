<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240621025811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position_state DROP broker_id');
        $this->addSql('ALTER TABLE position_state DROP exit');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN entry_time TO time');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN entry TO price_level');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE position_state ADD broker_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE position_state ADD exit DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN time TO entry_time');
        $this->addSql('ALTER TABLE position_state RENAME COLUMN price_level TO entry');
    }
}
