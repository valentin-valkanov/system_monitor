<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211161508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE position_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE position_state_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE position (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE position_state (id INT NOT NULL, position_id INT NOT NULL, time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, symbol VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, system VARCHAR(255) NOT NULL, strategy VARCHAR(255) NOT NULL, asset_class VARCHAR(255) NOT NULL, volume DOUBLE PRECISION NOT NULL, price_level DOUBLE PRECISION NOT NULL, stop_loss DOUBLE PRECISION NOT NULL, commission DOUBLE PRECISION NOT NULL, dividend DOUBLE PRECISION NOT NULL, swap DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, grade VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F533626DD842E46 ON position_state (position_id)');
        $this->addSql('COMMENT ON COLUMN position_state.time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE position_state ADD CONSTRAINT FK_2F533626DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE position_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE position_state_id_seq CASCADE');
        $this->addSql('ALTER TABLE position_state DROP CONSTRAINT FK_2F533626DD842E46');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE position_state');
    }
}
