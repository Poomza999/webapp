CREATE TABLE IF NOT EXISTS `products` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(80),
    `description` TEXT,
    `picture_url` TEXT,
    `price` FLOAT,
);

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(50),
    `password` VARCHAR(255),
    `email` VARCHAR(255),
    `telephone_number` VARCHAR(13),
    `user_role` ENUM('user', 'admin')
);

-- ALTER TABLE `users`
-- ADD COLUMN
--     `email` VARCHAR(255),
--     `telephone_number` VARCHAR(13)

-- ALTER TABLE `users` MODIFY `email` AFTER `password`