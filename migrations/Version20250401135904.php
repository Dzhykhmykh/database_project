<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250401135904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE job_title_job_responsibility (job_title_id INT NOT NULL, job_responsibility_id INT NOT NULL, PRIMARY KEY(job_title_id, job_responsibility_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B01D83256DD822C6 ON job_title_job_responsibility (job_title_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B01D83253C5C4842 ON job_title_job_responsibility (job_responsibility_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_title_job_responsibility ADD CONSTRAINT FK_B01D83256DD822C6 FOREIGN KEY (job_title_id) REFERENCES job_title (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_title_job_responsibility ADD CONSTRAINT FK_B01D83253C5C4842 FOREIGN KEY (job_responsibility_id) REFERENCES job_responsibility (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_title_job_responsibility DROP CONSTRAINT FK_B01D83256DD822C6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE job_title_job_responsibility DROP CONSTRAINT FK_B01D83253C5C4842
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE job_title_job_responsibility
        SQL);
    }
}
