<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209220436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours ADD section_id INT NOT NULL, CHANGE duree duree INT NOT NULL');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CD823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('CREATE INDEX IDX_FDCA8C9CD823E37A ON cours (section_id)');
        $this->addSql('ALTER TABLE section ADD formation_id INT NOT NULL, CHANGE duree duree INT NOT NULL');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF5200282E ON section (formation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CD823E37A');
        $this->addSql('DROP INDEX IDX_FDCA8C9CD823E37A ON cours');
        $this->addSql('ALTER TABLE cours DROP section_id, CHANGE duree duree INT DEFAULT NULL');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF5200282E');
        $this->addSql('DROP INDEX IDX_2D737AEF5200282E ON section');
        $this->addSql('ALTER TABLE section DROP formation_id, CHANGE duree duree INT DEFAULT NULL');
    }
}
