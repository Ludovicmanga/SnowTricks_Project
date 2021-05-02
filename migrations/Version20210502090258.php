<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210502090258 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE comment_content comment_content VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE trick_group_id trick_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE profile_picture_path profile_picture_path VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE id id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE comment_content comment_content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE image CHANGE id id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE trick CHANGE trick_group_id trick_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE profile_picture_path profile_picture_path VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
