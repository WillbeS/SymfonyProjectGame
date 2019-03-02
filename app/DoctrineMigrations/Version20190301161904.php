<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190301161904 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE military_campaigns ADD origin_id INT DEFAULT NULL, ADD destination_id INT DEFAULT NULL, ADD journey_task_id INT DEFAULT NULL, CHANGE army army VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE military_campaigns ADD CONSTRAINT FK_EBDAE66556A273CC FOREIGN KEY (origin_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE military_campaigns ADD CONSTRAINT FK_EBDAE665816C6140 FOREIGN KEY (destination_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE military_campaigns ADD CONSTRAINT FK_EBDAE665B2B1E827 FOREIGN KEY (journey_task_id) REFERENCES scheduled_tasks (id)');
        $this->addSql('CREATE INDEX IDX_EBDAE66556A273CC ON military_campaigns (origin_id)');
        $this->addSql('CREATE INDEX IDX_EBDAE665816C6140 ON military_campaigns (destination_id)');
        $this->addSql('CREATE INDEX IDX_EBDAE665B2B1E827 ON military_campaigns (journey_task_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE military_campaigns DROP FOREIGN KEY FK_EBDAE66556A273CC');
        $this->addSql('ALTER TABLE military_campaigns DROP FOREIGN KEY FK_EBDAE665816C6140');
        $this->addSql('ALTER TABLE military_campaigns DROP FOREIGN KEY FK_EBDAE665B2B1E827');
        $this->addSql('DROP INDEX IDX_EBDAE66556A273CC ON military_campaigns');
        $this->addSql('DROP INDEX IDX_EBDAE665816C6140 ON military_campaigns');
        $this->addSql('DROP INDEX IDX_EBDAE665B2B1E827 ON military_campaigns');
        $this->addSql('ALTER TABLE military_campaigns DROP origin_id, DROP destination_id, DROP journey_task_id, CHANGE army army VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
