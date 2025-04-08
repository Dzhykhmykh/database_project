<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250403131459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE employee (id SERIAL NOT NULL, working_status_id INT NOT NULL, job_title_id INT NOT NULL, department_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, second_name VARCHAR(255) NOT NULL, patronymic VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D9F75A1A356530C ON employee (working_status_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D9F75A16DD822C6 ON employee (job_title_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5D9F75A1AE80F5DF ON employee (department_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A356530C FOREIGN KEY (working_status_id) REFERENCES working_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16DD822C6 FOREIGN KEY (job_title_id) REFERENCES job_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1A356530C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A16DD822C6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1AE80F5DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employee
        SQL);
    }
}
