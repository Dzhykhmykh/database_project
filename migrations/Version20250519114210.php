<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519114210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position RENAME start_date TO date_from
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position RENAME end_time TO date_to
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position RENAME date_from TO start_date
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position RENAME date_to TO end_time
        SQL);
    }
}
