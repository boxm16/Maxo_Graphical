<?php

require_once 'DataBaseConnection.php';
require_once 'Pet4U/Item.php';

class DataBaseTools_pet4U {

    private $connection;

    function __construct() {

        $dataBaseConnection = new DataBaseConnection();
        if ($_SERVER["SERVER_NAME"] == 'localhost') {
            $this->connection = $dataBaseConnection->getLocalhostConnection();
        } else {
            echo 'out server';
            $this->connection = $dataBaseConnection->getLocalhostConnectionOnServer();
        }
    }

    function createInvoiceTable() {
        $sql = "CREATE TABLE `invoice` (`firm_afm` int(10) NOT NULL,  `firm_title` VARCHAR(50) NOT NULL,  `number` VARCHAR(10) NOT NULL,  `date_stamp` DATE NOT NULL,  `notes` VARCHAR(250) NULL,  PRIMARY KEY (`number`))  ENGINE = InnoDB  DEFAULT CHARACTER SET = utf8;";
        try {
            $this->connection->exec($sql);
            echo "Table 'invoice' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'invoice' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    function createItemeTable() {
        $sql = "CREATE TABLE `item` (`id` VARCHAR(20) NOT NULL,  `barcode` VARCHAR(48) NOT NULL,  `description` VARCHAR(250) NOT NULL,  `notes` VARCHAR(250) NULL,   PRIMARY KEY (`id`))   ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;";
        try {
            $this->connection->exec($sql);
            echo "Table 'item' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'item' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    function createInvoiceItemeTable() {
        $sql = "CREATE TABLE `invoice_item` (`invoice_number` VARCHAR(10) NOT NULL,  `item_id` VARCHAR(20) NOT NULL,  `quantity` INT(10) NOT NULL,  `notes` VARCHAR(250) NULL,  INDEX `invoice_number_idx` (`invoice_number` ASC) VISIBLE,  INDEX `item_id_idx` (`item_id` ASC) VISIBLE,  CONSTRAINT `invoice_number`  FOREIGN KEY (`invoice_number`)  REFERENCES `pet4u_db`.`invoice` (`number`)  ON DELETE NO ACTION  ON UPDATE NO ACTION,  CONSTRAINT `item_id`  FOREIGN KEY (`item_id`)  REFERENCES `pet4u_db`.`item` (`id`)  ON DELETE NO ACTION  ON UPDATE NO ACTION)  ENGINE = InnoDB DEFAULT CHARACTER SET = utf8;";
        try {
            $this->connection->exec($sql);
            echo "Table 'invoice_item' created successfully" . "<br>";
        } catch (\PDOException $e) {
            if ($e->getCode() == "42S01") {
                echo "Table 'invoice_item' already exists" . "<br>";
            } else {
                echo $e->getMessage() . " Error Code:";
                echo $e->getCode() . "<br>";
            }
        }
    }

    function deleteInvoiceTable() {
        $sql = "DROP TABLE invoice";
        try {
            $this->connection->exec($sql);
            echo "Table 'invoice' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    function deleteItemTable() {

        $sql = "DROP TABLE item";
        try {
            $this->connection->exec($sql);
            echo "Table 'item' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    function deleteInvoiceItemTable() {
        $sql = "DROP TABLE invoice_item";

        try {
            $this->connection->exec($sql);
            echo "Table 'invoice_item' deleted successfully" . "<br>";
        } catch (\PDOException $e) {
            echo $e->getMessage() . " Error Code:";
            echo $e->getCode() . "<br>";
        }
    }

    //-----------SAVE PART--------

    function saveItem($item) {
        $sql = "INSERT INTO item (id, barcode, description, notes) VALUES (?,?,?,?);";

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute([$item->getId(), $item->getBarcode(), $item->getDescription(), $item->getNotes()]);
            return "Item inserted successfully";
        } catch (\PDOException $e) {
            return $e->getMessage() . " Error Code:" . $e->getCode();
        }
    }

}
