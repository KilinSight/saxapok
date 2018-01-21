-- 20.01.2018
CREATE TABLE `AGUProject`.`vacancy`
    ( `id` INT NOT NULL AUTO_INCREMENT ,
    `region` TEXT NOT NULL ,
    `organization` TEXT NOT NULL ,
    `industry` TEXT NOT NULL ,
    `profession` TEXT NOT NULL ,
    `creationDate` DATE NOT NULL ,
    `datePosted` DATETIME NOT NULL ,
    `identifier` TEXT NOT NULL ,
    `hiringOrganization` TEXT NOT NULL ,
    `baseSalary` DOUBLE NOT NULL ,
    `title` TEXT NOT NULL ,
    `employmentType` TEXT NOT NULL ,
    `workHours` TEXT NOT NULL ,
    `responsibilities` TEXT NOT NULL ,
    `incentiveCompensation` TEXT NOT NULL ,
    `requirements` TEXT NOT NULL ,
    `socialProtecteds` TEXT NOT NULL ,
    `metroStations` TEXT NOT NULL ,
    `source` TEXT NOT NULL ,
    `workPlaces` INT NOT NULL ,
    `additionalInfo` TEXT NOT NULL ,
    `deleted` BOOLEAN NOT NULL ,
    `vacUrl` TEXT NOT NULL ,
    PRIMARY KEY (`id`)) ENGINE = InnoDB;