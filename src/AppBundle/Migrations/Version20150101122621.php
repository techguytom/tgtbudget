<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150101122621 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts CHANGE available_balance_amount current_balance_amount INT NOT NULL, CHANGE available_balance_currency current_balance_currency VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE account_types ADD credit_account TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE bills ADD pay_to_account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD088EF6A35 FOREIGN KEY (pay_to_account_id) REFERENCES accounts (id)');
        $this->addSql('CREATE INDEX IDX_22775DD088EF6A35 ON bills (pay_to_account_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE account_types DROP credit_account');
        $this->addSql('ALTER TABLE accounts CHANGE current_balance_amount available_balance_amount INT NOT NULL, CHANGE current_balance_currency available_balance_currency VARCHAR(64) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD088EF6A35');
        $this->addSql('DROP INDEX IDX_22775DD088EF6A35 ON bills');
        $this->addSql('ALTER TABLE bills DROP pay_to_account_id');
    }
}
