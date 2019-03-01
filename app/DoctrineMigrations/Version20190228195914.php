<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190228195914 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE units ADD training_task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B0744950889188 FOREIGN KEY (training_task_id) REFERENCES scheduled_tasks (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E9B0744950889188 ON units (training_task_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B0744950889188');
        $this->addSql('DROP INDEX UNIQ_E9B0744950889188 ON units');
        $this->addSql('ALTER TABLE units DROP training_task_id');
    }
}
