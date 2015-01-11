<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150111100349 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bills ADD pay_from_account_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD08EF26942 FOREIGN KEY (pay_from_account_id) REFERENCES accounts (id)');
        $this->addSql('CREATE INDEX IDX_22775DD08EF26942 ON bills (pay_from_account_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD08EF26942');
        $this->addSql('DROP INDEX IDX_22775DD08EF26942 ON bills');
        $this->addSql('ALTER TABLE bills DROP pay_from_account_id');
    }
}
