USE `generator-example`;
DELETE FROM element_list WHERE 1;
ALTER TABLE element_list AUTO_INCREMENT = 1;
USE `test`;
DELETE FROM element WHERE 1;
ALTER TABLE element AUTO_INCREMENT = 1;