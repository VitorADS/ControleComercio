<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107132932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            insert into status (id,	name, new, finished)
            values
            (1, 'Novo', true, false),
            (2, 'Finalizado', false, true);
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("delete from status where id = 1;");
        $this->addSql("delete from status where id = 2;");
    }
}
