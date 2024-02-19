<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218220420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marque_page ADD mot_cles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marque_page ADD CONSTRAINT FK_19292F83855234A9 FOREIGN KEY (mot_cles_id) REFERENCES mots_cles (id)');
        $this->addSql('CREATE INDEX IDX_19292F83855234A9 ON marque_page (mot_cles_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marque_page DROP FOREIGN KEY FK_19292F83855234A9');
        $this->addSql('DROP INDEX IDX_19292F83855234A9 ON marque_page');
        $this->addSql('ALTER TABLE marque_page DROP mot_cles_id');
    }
}
