CREATE DATABASE IF NOT EXISTS test;
USE test;

DROP TABLE IF EXISTS test;

CREATE TABLE test.Test (
  Id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Nom NVARCHAR(45) NOT NULL DEFAULT '',
  Prenom NVARCHAR(45) NOT NULL DEFAULT '',
  Naiss DATE NULL,
  Adresse Nvarchar(45) not Null default '',
  CodePostal Nvarchar(6) not null default '',
  Ville NVarchar(45) not null default '',
  Telephone nvarchar(14) not null default '',
  Mail nvarchar(50) not null default '',
  Secu nvarchar(45) not null default '',
  PRIMARY KEY(Id)
)
ENGINE = InnoDB;
