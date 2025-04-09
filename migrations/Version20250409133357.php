<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409133357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE days_off (id SERIAL NOT NULL, days_off_type_id INT NOT NULL, employee_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_from TIMESTAMP(0) WITH TIME ZONE NOT NULL, date_to TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C98E6BCA136CD10C ON days_off (days_off_type_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C98E6BCA8C03F15C ON days_off (employee_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE days_off ADD CONSTRAINT FK_C98E6BCA136CD10C FOREIGN KEY (days_off_type_id) REFERENCES days_off_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE days_off ADD CONSTRAINT FK_C98E6BCA8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE days_off DROP CONSTRAINT FK_C98E6BCA136CD10C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE days_off DROP CONSTRAINT FK_C98E6BCA8C03F15C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE days_off
        SQL);
    }
}
