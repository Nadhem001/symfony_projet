<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520200748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteurs DROP FOREIGN KEY FK_6DD7D42AEBF07F38');
        $this->addSql('DROP INDEX IDX_6DD7D42AEBF07F38 ON auteurs');
        $this->addSql('ALTER TABLE auteurs DROP livres_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE auteurs ADD livres_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auteurs ADD CONSTRAINT FK_6DD7D42AEBF07F38 FOREIGN KEY (livres_id) REFERENCES livres (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_6DD7D42AEBF07F38 ON auteurs (livres_id)');
    }
}
