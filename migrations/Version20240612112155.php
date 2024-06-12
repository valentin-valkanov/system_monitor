<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612112155 extends AbstractMigration
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
        $this->addSql('CREATE TABLE brokerId (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE position_state (id INT NOT NULL, entry_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, brokerId VARCHAR(255) NOT NULL, symbol VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, volume DOUBLE PRECISION NOT NULL, entry DOUBLE PRECISION NOT NULL, stop_loss DOUBLE PRECISION NOT NULL, take_profit DOUBLE PRECISION DEFAULT NULL, exit_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, exit DOUBLE PRECISION DEFAULT NULL, commission DOUBLE PRECISION NOT NULL, swap DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, system VARCHAR(255) NOT NULL, strategy VARCHAR(255) NOT NULL, asset_class VARCHAR(255) NOT NULL, grade VARCHAR(255) DEFAULT NULL, dividend DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN position_state.entry_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN position_state.exit_time IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE position_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE position_state_id_seq CASCADE');
        $this->addSql('DROP TABLE brokerId');
        $this->addSql('DROP TABLE position_state');
    }
}
