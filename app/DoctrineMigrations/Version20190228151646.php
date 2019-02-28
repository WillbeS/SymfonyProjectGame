<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190228151646 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buildings ADD upgrade_task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE buildings ADD CONSTRAINT FK_9A51B6A770E80163 FOREIGN KEY (upgrade_task_id) REFERENCES scheduled_tasks (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9A51B6A770E80163 ON buildings (upgrade_task_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buildings DROP FOREIGN KEY FK_9A51B6A770E80163');
        $this->addSql('DROP INDEX UNIQ_9A51B6A770E80163 ON buildings');
        $this->addSql('ALTER TABLE buildings DROP upgrade_task_id');
    }
}
