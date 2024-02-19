<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218223842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marque_page_mots_cles (marque_page_id INT NOT NULL, mots_cles_id INT NOT NULL, INDEX IDX_DD7D38C7D59CC0F1 (marque_page_id), INDEX IDX_DD7D38C7C0BE80DB (mots_cles_id), PRIMARY KEY(marque_page_id, mots_cles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marque_page_mots_cles ADD CONSTRAINT FK_DD7D38C7D59CC0F1 FOREIGN KEY (marque_page_id) REFERENCES marque_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marque_page_mots_cles ADD CONSTRAINT FK_DD7D38C7C0BE80DB FOREIGN KEY (mots_cles_id) REFERENCES mots_cles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marque_page_mots_cles DROP FOREIGN KEY FK_DD7D38C7D59CC0F1');
        $this->addSql('ALTER TABLE marque_page_mots_cles DROP FOREIGN KEY FK_DD7D38C7C0BE80DB');
        $this->addSql('DROP TABLE marque_page_mots_cles');
    }
}
