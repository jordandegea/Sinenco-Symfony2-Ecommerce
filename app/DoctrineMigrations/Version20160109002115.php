<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160109002115 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE languagepage_media');
        $this->addSql('ALTER TABLE Tab DROP FOREIGN KEY FK_4BAE05EC3DA5256D');
        $this->addSql('DROP INDEX IDX_4BAE05EC3DA5256D ON Tab');
        $this->addSql('ALTER TABLE Tab DROP image_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE languagepage_media (languagepage_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_8751842DE966BD01 (languagepage_id), INDEX IDX_8751842DEA9FDD75 (media_id), PRIMARY KEY(languagepage_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE languagepage_media ADD CONSTRAINT FK_8751842DE966BD01 FOREIGN KEY (languagepage_id) REFERENCES LanguagePage (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE languagepage_media ADD CONSTRAINT FK_8751842DEA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE Tab ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Tab ADD CONSTRAINT FK_4BAE05EC3DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('CREATE INDEX IDX_4BAE05EC3DA5256D ON Tab (image_id)');
    }
}
