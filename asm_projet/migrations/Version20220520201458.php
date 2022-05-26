<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520201458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livres ADD auteurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A4AE784107 FOREIGN KEY (auteurs_id) REFERENCES auteurs (id)');
        $this->addSql('CREATE INDEX IDX_927187A4AE784107 ON livres (auteurs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A4AE784107');
        $this->addSql('DROP INDEX IDX_927187A4AE784107 ON livres');
        $this->addSql('ALTER TABLE livres DROP auteurs_id');
    }
}
