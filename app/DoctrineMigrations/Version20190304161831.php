<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190304161831 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE army_journeys');
        $this->addSql('ALTER TABLE resources DROP update_time');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE army_journeys (id INT AUTO_INCREMENT NOT NULL, origin_id INT DEFAULT NULL, destination_id INT DEFAULT NULL, start_date DATETIME NOT NULL, due_date DATETIME NOT NULL, troops LONGTEXT NOT NULL COLLATE utf8_unicode_ci, duration INT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, purpose INT NOT NULL, INDEX IDX_6EAB659856A273CC (origin_id), INDEX IDX_6EAB6598816C6140 (destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE army_journeys ADD CONSTRAINT FK_6EAB659856A273CC FOREIGN KEY (origin_id) REFERENCES grid_cells (id)');
        $this->addSql('ALTER TABLE army_journeys ADD CONSTRAINT FK_6EAB6598816C6140 FOREIGN KEY (destination_id) REFERENCES grid_cells (id)');
        $this->addSql('ALTER TABLE resources ADD update_time DATETIME NOT NULL');
    }
}
