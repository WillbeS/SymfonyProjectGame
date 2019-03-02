<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190302110043 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE military_campaigns DROP FOREIGN KEY FK_EBDAE665B2B1E827');
        $this->addSql('DROP INDEX IDX_EBDAE665B2B1E827 ON military_campaigns');
        $this->addSql('ALTER TABLE military_campaigns ADD task_type INT NOT NULL, ADD start_date DATETIME NOT NULL, ADD due_date DATETIME NOT NULL, ADD duration INT NOT NULL, DROP journey_task_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE military_campaigns ADD journey_task_id INT DEFAULT NULL, DROP task_type, DROP start_date, DROP due_date, DROP duration');
        $this->addSql('ALTER TABLE military_campaigns ADD CONSTRAINT FK_EBDAE665B2B1E827 FOREIGN KEY (journey_task_id) REFERENCES scheduled_tasks (id)');
        $this->addSql('CREATE INDEX IDX_EBDAE665B2B1E827 ON military_campaigns (journey_task_id)');
    }
}
