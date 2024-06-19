<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619120426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE position_state ADD state VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE position_state DROP take_profit');
        $this->addSql('ALTER TABLE position_state DROP exit_time');
        $this->addSql('ALTER TABLE position_state ALTER grade SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE position_state ADD take_profit DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE position_state ADD exit_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE position_state DROP state');
        $this->addSql('ALTER TABLE position_state ALTER grade DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN position_state.exit_time IS \'(DC2Type:datetime_immutable)\'');
    }
}
