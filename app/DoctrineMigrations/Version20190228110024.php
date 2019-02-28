<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190228110024 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scheduled_tasks ADD platform_id INT DEFAULT NULL, ADD task_type INT NOT NULL, ADD duration INT NOT NULL, DROP entity_id, DROP taskType, CHANGE duedate due_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE scheduled_tasks ADD CONSTRAINT FK_12253CBCFFE6496F FOREIGN KEY (platform_id) REFERENCES platforms (id)');
        $this->addSql('CREATE INDEX IDX_12253CBCFFE6496F ON scheduled_tasks (platform_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE scheduled_tasks DROP FOREIGN KEY FK_12253CBCFFE6496F');
        $this->addSql('DROP INDEX IDX_12253CBCFFE6496F ON scheduled_tasks');
        $this->addSql('ALTER TABLE scheduled_tasks ADD entity_id INT NOT NULL, ADD taskType INT NOT NULL, DROP platform_id, DROP task_type, DROP duration, CHANGE due_date dueDate DATETIME NOT NULL');
    }
}
