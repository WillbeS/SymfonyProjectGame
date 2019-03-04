<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190304164328 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E37B2710BE');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E392610AE9');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E3BA8E87C4');
        $this->addSql('DROP INDEX IDX_178186E3BA8E87C4 ON platforms');
        $this->addSql('DROP INDEX IDX_178186E37B2710BE ON platforms');
        $this->addSql('DROP INDEX IDX_178186E392610AE9 ON platforms');
        $this->addSql('ALTER TABLE platforms DROP wood_id, DROP supplies_id, DROP food_id');
        $this->addSql('ALTER TABLE units DROP start_build');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE platforms ADD wood_id INT DEFAULT NULL, ADD supplies_id INT DEFAULT NULL, ADD food_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E37B2710BE FOREIGN KEY (wood_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E392610AE9 FOREIGN KEY (supplies_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E3BA8E87C4 FOREIGN KEY (food_id) REFERENCES resources (id)');
        $this->addSql('CREATE INDEX IDX_178186E3BA8E87C4 ON platforms (food_id)');
        $this->addSql('CREATE INDEX IDX_178186E37B2710BE ON platforms (wood_id)');
        $this->addSql('CREATE INDEX IDX_178186E392610AE9 ON platforms (supplies_id)');
        $this->addSql('ALTER TABLE units ADD start_build DATETIME DEFAULT NULL');
    }
}
