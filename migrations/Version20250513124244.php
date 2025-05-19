<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513124244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE employee_position (id SERIAL NOT NULL, employee_id INT NOT NULL, job_title_id INT NOT NULL, department_id INT NOT NULL, working_status_id INT NOT NULL, start_date DATE NOT NULL, end_time DATE, salary INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D613B5328C03F15C ON employee_position (employee_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D613B5326DD822C6 ON employee_position (job_title_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D613B532AE80F5DF ON employee_position (department_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D613B532A356530C ON employee_position (working_status_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ADD CONSTRAINT FK_D613B5328C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ADD CONSTRAINT FK_D613B5326DD822C6 FOREIGN KEY (job_title_id) REFERENCES job_title (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ADD CONSTRAINT FK_D613B532AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position ADD CONSTRAINT FK_D613B532A356530C FOREIGN KEY (working_status_id) REFERENCES working_status (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position DROP CONSTRAINT FK_D613B5328C03F15C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position DROP CONSTRAINT FK_D613B5326DD822C6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position DROP CONSTRAINT FK_D613B532AE80F5DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE employee_position DROP CONSTRAINT FK_D613B532A356530C
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE employee_position
        SQL);
    }
}
