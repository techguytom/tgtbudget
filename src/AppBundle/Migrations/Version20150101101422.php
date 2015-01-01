<?php

namespace AppBundle\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150101101422 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accounts (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, account_type INT DEFAULT NULL, name VARCHAR(255) NOT NULL, credit_line_amount INT DEFAULT NULL, credit_line_currency VARCHAR(64) DEFAULT NULL, available_balance_amount INT NOT NULL, available_balance_currency VARCHAR(64) NOT NULL, INDEX IDX_CAC89EACA76ED395 (user_id), INDEX IDX_CAC89EAC4DD083 (account_type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE account_types (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6FBF5041A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bills (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, budget_amount INT NOT NULL, budget_currency VARCHAR(64) NOT NULL, due_date DATE NOT NULL, recurring TINYINT(1) NOT NULL, paid TINYINT(1) NOT NULL, INDEX IDX_22775DD012469DE2 (category_id), INDEX IDX_22775DD0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3AF34668A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, transaction_lead_time INT NOT NULL, INDEX IDX_E545A0C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, account_id INT DEFAULT NULL, category_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, transaction_amount INT NOT NULL, transaction_currency VARCHAR(64) NOT NULL, INDEX IDX_EAA81A4C9B6B5FBA (account_id), INDEX IDX_EAA81A4C12469DE2 (category_id), INDEX IDX_EAA81A4C1A8C12F5 (bill_id), INDEX IDX_EAA81A4CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tgt_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A636333B92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_A636333BA0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbbc_money_doctrine_storage_ratios (id INT AUTO_INCREMENT NOT NULL, currency_code VARCHAR(3) NOT NULL, ratio DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_1168A609FDA273EC (currency_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tbbc_money_ratio_history (id INT AUTO_INCREMENT NOT NULL, currency_code VARCHAR(3) NOT NULL, reference_currency_code VARCHAR(3) NOT NULL, ratio DOUBLE PRECISION NOT NULL, saved_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACA76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EAC4DD083 FOREIGN KEY (account_type) REFERENCES account_types (id)');
        $this->addSql('ALTER TABLE account_types ADD CONSTRAINT FK_6FBF5041A76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD012469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE bills ADD CONSTRAINT FK_22775DD0A76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C9B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C1A8C12F5 FOREIGN KEY (bill_id) REFERENCES bills (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES tgt_users (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C9B6B5FBA');
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EAC4DD083');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C1A8C12F5');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD012469DE2');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C12469DE2');
        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EACA76ED395');
        $this->addSql('ALTER TABLE account_types DROP FOREIGN KEY FK_6FBF5041A76ED395');
        $this->addSql('ALTER TABLE bills DROP FOREIGN KEY FK_22775DD0A76ED395');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668A76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE account_types');
        $this->addSql('DROP TABLE bills');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE tgt_users');
        $this->addSql('DROP TABLE tbbc_money_doctrine_storage_ratios');
        $this->addSql('DROP TABLE tbbc_money_ratio_history');
    }
}
