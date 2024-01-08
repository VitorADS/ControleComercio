<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231224215948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE purchase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE purchase_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE purchase (id INT NOT NULL, user_system_id INT NOT NULL, total DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6117D13BD075A241 ON purchase (user_system_id)');
        $this->addSql('CREATE TABLE purchase_payment_type (purchase_id INT NOT NULL, payment_type_id INT NOT NULL, PRIMARY KEY(purchase_id, payment_type_id))');
        $this->addSql('CREATE INDEX IDX_694737FD558FBEB9 ON purchase_payment_type (purchase_id)');
        $this->addSql('CREATE INDEX IDX_694737FDDC058279 ON purchase_payment_type (payment_type_id)');
        $this->addSql('CREATE TABLE purchase_item (id INT NOT NULL, product_id INT NOT NULL, purchase_id INT NOT NULL, user_system_id INT NOT NULL, quantity INT NOT NULL, sub_total DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D4584665A ON purchase_item (product_id)');
        $this->addSql('CREATE INDEX IDX_6FA8ED7D558FBEB9 ON purchase_item (purchase_id)');
        $this->addSql('CREATE INDEX IDX_6FA8ED7DD075A241 ON purchase_item (user_system_id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BD075A241 FOREIGN KEY (user_system_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_payment_type ADD CONSTRAINT FK_694737FD558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_payment_type ADD CONSTRAINT FK_694737FDDC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7D558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_item ADD CONSTRAINT FK_6FA8ED7DD075A241 FOREIGN KEY (user_system_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE payment_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE purchase_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE purchase_item_id_seq CASCADE');
        $this->addSql('ALTER TABLE purchase DROP CONSTRAINT FK_6117D13BD075A241');
        $this->addSql('ALTER TABLE purchase_payment_type DROP CONSTRAINT FK_694737FD558FBEB9');
        $this->addSql('ALTER TABLE purchase_payment_type DROP CONSTRAINT FK_694737FDDC058279');
        $this->addSql('ALTER TABLE purchase_item DROP CONSTRAINT FK_6FA8ED7D4584665A');
        $this->addSql('ALTER TABLE purchase_item DROP CONSTRAINT FK_6FA8ED7D558FBEB9');
        $this->addSql('ALTER TABLE purchase_item DROP CONSTRAINT FK_6FA8ED7DD075A241');
        $this->addSql('DROP TABLE payment_type');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE purchase_payment_type');
        $this->addSql('DROP TABLE purchase_item');
    }
}
