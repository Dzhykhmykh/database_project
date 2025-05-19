<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513125054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a1a356530c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a16dd822c6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT fk_5d9f75a1ae80f5df
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5d9f75a1ae80f5df
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5d9f75a16dd822c6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_5d9f75a1a356530c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP working_status_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP job_title_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP department_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ALTER end_time SET NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ALTER end_time DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD working_status_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD job_title_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD department_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a1a356530c FOREIGN KEY (working_status_id) REFERENCES working_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a16dd822c6 FOREIGN KEY (job_title_id) REFERENCES job_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT fk_5d9f75a1ae80f5df FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_5d9f75a1ae80f5df ON employee (department_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_5d9f75a16dd822c6 ON employee (job_title_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_5d9f75a1a356530c ON employee (working_status_id)
        SQL);
    }
}
