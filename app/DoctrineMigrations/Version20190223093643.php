<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190223093643 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, current_platform_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, date_joined DATETIME NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), UNIQUE INDEX UNIQ_1483A5E94132E2B6 (current_platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_roles (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_51498A8EA76ED395 (user_id), INDEX IDX_51498A8ED60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE army_journeys (id INT AUTO_INCREMENT NOT NULL, origin_id INT DEFAULT NULL, destination_id INT DEFAULT NULL, start_date DATETIME NOT NULL, due_date DATETIME NOT NULL, troops LONGTEXT NOT NULL, duration INT NOT NULL, name VARCHAR(255) NOT NULL, purpose INT NOT NULL, INDEX IDX_6EAB659856A273CC (origin_id), INDEX IDX_6EAB6598816C6140 (destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE battle_reports (id INT AUTO_INCREMENT NOT NULL, attacker_id INT DEFAULT NULL, defender_id INT DEFAULT NULL, winner_id INT DEFAULT NULL, attacker_start_army LONGTEXT NOT NULL, attacker_end_army LONGTEXT DEFAULT NULL, defender_start_army LONGTEXT DEFAULT NULL, defender_end_army LONGTEXT DEFAULT NULL, rounds LONGTEXT DEFAULT NULL, created_on DATETIME NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3B80132665F8CAE3 (attacker_id), INDEX IDX_3B8013264A3E3B6F (defender_id), INDEX IDX_3B8013265DFCD4B8 (winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buildings (id INT AUTO_INCREMENT NOT NULL, game_building_id INT DEFAULT NULL, platform_id INT DEFAULT NULL, level INT NOT NULL, start_build DATETIME DEFAULT NULL, INDEX IDX_9A51B6A7937F3C10 (game_building_id), INDEX IDX_9A51B6A7FFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_buildings (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, wood_cost INT NOT NULL, food_cost INT NOT NULL, supplies_cost INT DEFAULT NULL, build_time INT NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_48F777A55E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resources (id INT AUTO_INCREMENT NOT NULL, resource_type_id INT DEFAULT NULL, building_id INT DEFAULT NULL, platform_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, update_time DATETIME NOT NULL, INDEX IDX_EF66EBAE98EC6B7B (resource_type_id), INDEX IDX_EF66EBAE4D2A7E12 (building_id), INDEX IDX_EF66EBAEFFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grid_cells (id INT AUTO_INCREMENT NOT NULL, terrain_id INT DEFAULT NULL, row INT NOT NULL, col INT NOT NULL, INDEX IDX_D082C9AD8A2D8B41 (terrain_id), INDEX search_idx (row, col), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_on DATETIME NOT NULL, INDEX IDX_DB021E96F624B39D (sender_id), INDEX IDX_DB021E961F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message_topics (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, about VARCHAR(255) NOT NULL, updated_on DATETIME NOT NULL, deleted_on DATETIME DEFAULT NULL, messages_count INT NOT NULL, INDEX IDX_A6D1DB36F624B39D (sender_id), INDEX IDX_A6D1DB36E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platforms (id INT AUTO_INCREMENT NOT NULL, food_id INT DEFAULT NULL, wood_id INT DEFAULT NULL, supplies_id INT DEFAULT NULL, grid_cell_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, res_update_time DATETIME NOT NULL, INDEX IDX_178186E3BA8E87C4 (food_id), INDEX IDX_178186E37B2710BE (wood_id), INDEX IDX_178186E392610AE9 (supplies_id), UNIQUE INDEX UNIQ_178186E3777FB0E6 (grid_cell_id), INDEX IDX_178186E3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE requirements (id INT AUTO_INCREMENT NOT NULL, game_building_id INT DEFAULT NULL, unit_type_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_70BEA1AA937F3C10 (game_building_id), INDEX IDX_70BEA1AA91058251 (unit_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_types (id INT AUTO_INCREMENT NOT NULL, game_building_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, base_income INT NOT NULL, UNIQUE INDEX UNIQ_728BF3025E237E06 (name), UNIQUE INDEX UNIQ_728BF302937F3C10 (game_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B63E2EC75E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduled_tasks (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, taskType INT NOT NULL, dueDate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrains (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, random_factor INT NOT NULL, UNIQUE INDEX UNIQ_A7A03A425E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE units (id INT AUTO_INCREMENT NOT NULL, unit_type_id INT DEFAULT NULL, building_id INT DEFAULT NULL, platform_id INT DEFAULT NULL, in_training INT NOT NULL, iddle INT NOT NULL, in_battle INT NOT NULL, start_build DATETIME DEFAULT NULL, is_available TINYINT(1) NOT NULL, INDEX IDX_E9B0744991058251 (unit_type_id), INDEX IDX_E9B074494D2A7E12 (building_id), INDEX IDX_E9B07449FFE6496F (platform_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_types (id INT AUTO_INCREMENT NOT NULL, game_building_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, woodCost INT NOT NULL, foodCost INT NOT NULL, suppliesCost INT NOT NULL, buildTime INT NOT NULL, speed INT NOT NULL, health INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_D202F73B5E237E06 (name), INDEX IDX_D202F73B937F3C10 (game_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_reports (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, report_id INT DEFAULT NULL, isRead TINYINT(1) NOT NULL, INDEX IDX_11FD38FA76ED395 (user_id), INDEX IDX_11FD38F4BD2A4C0 (report_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_topics (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, topic_id INT DEFAULT NULL, is_read TINYINT(1) NOT NULL, INDEX IDX_9E11C8A9A76ED395 (user_id), INDEX IDX_9E11C8A91F55203D (topic_id), UNIQUE INDEX user_topic (user_id, topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E94132E2B6 FOREIGN KEY (current_platform_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8EA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8ED60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
        $this->addSql('ALTER TABLE army_journeys ADD CONSTRAINT FK_6EAB659856A273CC FOREIGN KEY (origin_id) REFERENCES grid_cells (id)');
        $this->addSql('ALTER TABLE army_journeys ADD CONSTRAINT FK_6EAB6598816C6140 FOREIGN KEY (destination_id) REFERENCES grid_cells (id)');
        $this->addSql('ALTER TABLE battle_reports ADD CONSTRAINT FK_3B80132665F8CAE3 FOREIGN KEY (attacker_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE battle_reports ADD CONSTRAINT FK_3B8013264A3E3B6F FOREIGN KEY (defender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE battle_reports ADD CONSTRAINT FK_3B8013265DFCD4B8 FOREIGN KEY (winner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE buildings ADD CONSTRAINT FK_9A51B6A7937F3C10 FOREIGN KEY (game_building_id) REFERENCES game_buildings (id)');
        $this->addSql('ALTER TABLE buildings ADD CONSTRAINT FK_9A51B6A7FFE6496F FOREIGN KEY (platform_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE resources ADD CONSTRAINT FK_EF66EBAE98EC6B7B FOREIGN KEY (resource_type_id) REFERENCES resource_types (id)');
        $this->addSql('ALTER TABLE resources ADD CONSTRAINT FK_EF66EBAE4D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE resources ADD CONSTRAINT FK_EF66EBAEFFE6496F FOREIGN KEY (platform_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE grid_cells ADD CONSTRAINT FK_D082C9AD8A2D8B41 FOREIGN KEY (terrain_id) REFERENCES terrains (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E96F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E961F55203D FOREIGN KEY (topic_id) REFERENCES message_topics (id)');
        $this->addSql('ALTER TABLE message_topics ADD CONSTRAINT FK_A6D1DB36F624B39D FOREIGN KEY (sender_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE message_topics ADD CONSTRAINT FK_A6D1DB36E92F8F78 FOREIGN KEY (recipient_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E3BA8E87C4 FOREIGN KEY (food_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E37B2710BE FOREIGN KEY (wood_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E392610AE9 FOREIGN KEY (supplies_id) REFERENCES resources (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E3777FB0E6 FOREIGN KEY (grid_cell_id) REFERENCES grid_cells (id)');
        $this->addSql('ALTER TABLE platforms ADD CONSTRAINT FK_178186E3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE requirements ADD CONSTRAINT FK_70BEA1AA937F3C10 FOREIGN KEY (game_building_id) REFERENCES game_buildings (id)');
        $this->addSql('ALTER TABLE requirements ADD CONSTRAINT FK_70BEA1AA91058251 FOREIGN KEY (unit_type_id) REFERENCES unit_types (id)');
        $this->addSql('ALTER TABLE resource_types ADD CONSTRAINT FK_728BF302937F3C10 FOREIGN KEY (game_building_id) REFERENCES game_buildings (id)');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B0744991058251 FOREIGN KEY (unit_type_id) REFERENCES unit_types (id)');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B074494D2A7E12 FOREIGN KEY (building_id) REFERENCES buildings (id)');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B07449FFE6496F FOREIGN KEY (platform_id) REFERENCES platforms (id)');
        $this->addSql('ALTER TABLE unit_types ADD CONSTRAINT FK_D202F73B937F3C10 FOREIGN KEY (game_building_id) REFERENCES game_buildings (id)');
        $this->addSql('ALTER TABLE users_reports ADD CONSTRAINT FK_11FD38FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_reports ADD CONSTRAINT FK_11FD38F4BD2A4C0 FOREIGN KEY (report_id) REFERENCES battle_reports (id)');
        $this->addSql('ALTER TABLE users_topics ADD CONSTRAINT FK_9E11C8A9A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_topics ADD CONSTRAINT FK_9E11C8A91F55203D FOREIGN KEY (topic_id) REFERENCES message_topics (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8EA76ED395');
        $this->addSql('ALTER TABLE battle_reports DROP FOREIGN KEY FK_3B80132665F8CAE3');
        $this->addSql('ALTER TABLE battle_reports DROP FOREIGN KEY FK_3B8013264A3E3B6F');
        $this->addSql('ALTER TABLE battle_reports DROP FOREIGN KEY FK_3B8013265DFCD4B8');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E96F624B39D');
        $this->addSql('ALTER TABLE message_topics DROP FOREIGN KEY FK_A6D1DB36F624B39D');
        $this->addSql('ALTER TABLE message_topics DROP FOREIGN KEY FK_A6D1DB36E92F8F78');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E3A76ED395');
        $this->addSql('ALTER TABLE users_reports DROP FOREIGN KEY FK_11FD38FA76ED395');
        $this->addSql('ALTER TABLE users_topics DROP FOREIGN KEY FK_9E11C8A9A76ED395');
        $this->addSql('ALTER TABLE users_reports DROP FOREIGN KEY FK_11FD38F4BD2A4C0');
        $this->addSql('ALTER TABLE resources DROP FOREIGN KEY FK_EF66EBAE4D2A7E12');
        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B074494D2A7E12');
        $this->addSql('ALTER TABLE buildings DROP FOREIGN KEY FK_9A51B6A7937F3C10');
        $this->addSql('ALTER TABLE requirements DROP FOREIGN KEY FK_70BEA1AA937F3C10');
        $this->addSql('ALTER TABLE resource_types DROP FOREIGN KEY FK_728BF302937F3C10');
        $this->addSql('ALTER TABLE unit_types DROP FOREIGN KEY FK_D202F73B937F3C10');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E3BA8E87C4');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E37B2710BE');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E392610AE9');
        $this->addSql('ALTER TABLE army_journeys DROP FOREIGN KEY FK_6EAB659856A273CC');
        $this->addSql('ALTER TABLE army_journeys DROP FOREIGN KEY FK_6EAB6598816C6140');
        $this->addSql('ALTER TABLE platforms DROP FOREIGN KEY FK_178186E3777FB0E6');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E961F55203D');
        $this->addSql('ALTER TABLE users_topics DROP FOREIGN KEY FK_9E11C8A91F55203D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E94132E2B6');
        $this->addSql('ALTER TABLE buildings DROP FOREIGN KEY FK_9A51B6A7FFE6496F');
        $this->addSql('ALTER TABLE resources DROP FOREIGN KEY FK_EF66EBAEFFE6496F');
        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B07449FFE6496F');
        $this->addSql('ALTER TABLE resources DROP FOREIGN KEY FK_EF66EBAE98EC6B7B');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8ED60322AC');
        $this->addSql('ALTER TABLE grid_cells DROP FOREIGN KEY FK_D082C9AD8A2D8B41');
        $this->addSql('ALTER TABLE requirements DROP FOREIGN KEY FK_70BEA1AA91058251');
        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B0744991058251');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('DROP TABLE army_journeys');
        $this->addSql('DROP TABLE battle_reports');
        $this->addSql('DROP TABLE buildings');
        $this->addSql('DROP TABLE game_buildings');
        $this->addSql('DROP TABLE resources');
        $this->addSql('DROP TABLE grid_cells');
        $this->addSql('DROP TABLE messages');
        $this->addSql('DROP TABLE message_topics');
        $this->addSql('DROP TABLE platforms');
        $this->addSql('DROP TABLE requirements');
        $this->addSql('DROP TABLE resource_types');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE scheduled_tasks');
        $this->addSql('DROP TABLE terrains');
        $this->addSql('DROP TABLE units');
        $this->addSql('DROP TABLE unit_types');
        $this->addSql('DROP TABLE users_reports');
        $this->addSql('DROP TABLE users_topics');
    }
}
