<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107132833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment (id INT NOT NULL, payment_type_id INT NOT NULL, purchase_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6D28840DDC058279 ON payment (payment_type_id)');
        $this->addSql('CREATE INDEX IDX_6D28840D558FBEB9 ON payment (purchase_id)');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name VARCHAR(255) NOT NULL, new BOOLEAN NOT NULL, finished BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE status_purchase (status_id INT NOT NULL, purchase_id INT NOT NULL, PRIMARY KEY(status_id, purchase_id))');
        $this->addSql('CREATE INDEX IDX_FCCD76E36BF700BD ON status_purchase (status_id)');
        $this->addSql('CREATE INDEX IDX_FCCD76E3558FBEB9 ON status_purchase (purchase_id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DDC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_purchase ADD CONSTRAINT FK_FCCD76E36BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status_purchase ADD CONSTRAINT FK_FCCD76E3558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_payment_type DROP CONSTRAINT fk_694737fd558fbeb9');
        $this->addSql('ALTER TABLE purchase_payment_type DROP CONSTRAINT fk_694737fddc058279');
        $this->addSql('DROP TABLE purchase_payment_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE payment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('CREATE TABLE purchase_payment_type (purchase_id INT NOT NULL, payment_type_id INT NOT NULL, PRIMARY KEY(purchase_id, payment_type_id))');
        $this->addSql('CREATE INDEX idx_694737fddc058279 ON purchase_payment_type (payment_type_id)');
        $this->addSql('CREATE INDEX idx_694737fd558fbeb9 ON purchase_payment_type (purchase_id)');
        $this->addSql('ALTER TABLE purchase_payment_type ADD CONSTRAINT fk_694737fd558fbeb9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_payment_type ADD CONSTRAINT fk_694737fddc058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840DDC058279');
        $this->addSql('ALTER TABLE payment DROP CONSTRAINT FK_6D28840D558FBEB9');
        $this->addSql('ALTER TABLE status_purchase DROP CONSTRAINT FK_FCCD76E36BF700BD');
        $this->addSql('ALTER TABLE status_purchase DROP CONSTRAINT FK_FCCD76E3558FBEB9');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE status_purchase');
    }
}
