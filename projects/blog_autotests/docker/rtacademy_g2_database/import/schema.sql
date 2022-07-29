
-- comments_statuses
CREATE TABLE `comments_statuses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
);

-- posts_statuses
CREATE TABLE `posts_statuses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
);

-- users_roles
CREATE TABLE `users_roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
);

-- users_statuses
CREATE TABLE `users_statuses` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(128) NOT NULL,
    `title` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
);

-- posts_categories
CREATE TABLE `posts_categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(128) NOT NULL,
    `alias` varchar(128) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `alias` (`alias`)
);

-- posts_covers
CREATE TABLE `posts_covers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `filename` varchar(128) NOT NULL,
    `alt` varchar(128),
    PRIMARY KEY (`id`),
    UNIQUE KEY `filename` (`filename`)
);

-- posts_tags
CREATE TABLE `posts_tags` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `tag` varchar(64) NOT NULL,
    `alias` varchar(64) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `tag` (`tag`),
    UNIQUE KEY `alias` (`alias`)
);

-- website_menu
CREATE TABLE `website_menu` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(128) NOT NULL,
    `href` varchar(255) NOT NULL,
    `order` tinyint(4) NOT NULL,
    PRIMARY KEY (`id`)
);

--

-- users
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` varchar(32) NOT NULL,
    `password` varchar(128) NOT NULL,
    `email` varchar(255) NOT NULL,
    `lastname` varchar(128) NOT NULL,
    `firstname` varchar(128) NOT NULL,
    `role_id` int(11) NOT NULL,
    `status_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`),
    UNIQUE KEY `login` (`login`),
    FOREIGN KEY (`role_id`) REFERENCES `users_roles` (`id`),
    FOREIGN KEY (`status_id`) REFERENCES `users_statuses` (`id`)
);

-- posts
CREATE TABLE `posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(128) NOT NULL,
    `alias` varchar(128) NOT NULL,
    `description` varchar(255) NOT NULL,
    `content` text NOT NULL,
    `publish_date` datetime NOT NULL,
    `author_id` int(11) NOT NULL,
    `category_id` int(11) NOT NULL,
    `cover_id` int(11) NOT NULL,
    `status_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
    FOREIGN KEY (`category_id`) REFERENCES `posts_categories` (`id`),
    FOREIGN KEY (`cover_id`) REFERENCES `posts_covers` (`id`),
    FOREIGN KEY (`status_id`) REFERENCES `posts_statuses` (`id`)
);

-- posts_comments
CREATE TABLE `posts_comments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `author_id` int(11) NOT NULL,
    `publish_date` datetime NOT NULL,
    `comment` text DEFAULT NULL,
    `status_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`status_id`) REFERENCES `comments_statuses` (`id`),
    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
);

-- posts_to_tags
CREATE TABLE `posts_to_tags` (
    `post_id` int(11) NOT NULL,
    `tag_id` int(11) NOT NULL,
    PRIMARY KEY (`post_id`,`tag_id`),
    FOREIGN KEY (`tag_id`) REFERENCES `posts_tags` (`id`),
    FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`)
);

--

ALTER TABLE comments_statuses AUTO_INCREMENT=101;

INSERT INTO comments_statuses (name, title)
VALUES
    ('active', 'Активний'),
    ('notmoderated', 'Не відмодерований'),
    ('deleted', 'Видалений');

ALTER TABLE posts_statuses AUTO_INCREMENT=201;

INSERT INTO posts_statuses (name, title)
VALUES
    ('active', 'Активний'),
    ('notactive', 'Неактивний'),
    ('deleted', 'Видалений');

ALTER TABLE users_statuses AUTO_INCREMENT=301;

INSERT INTO users_statuses (name, title)
VALUES
    ('active', 'Активний'),
    ('notactive', 'Неактивний'),
    ('deleted', 'Видалений');

ALTER TABLE users_roles AUTO_INCREMENT=401;

INSERT INTO users_roles (name, title)
VALUES
    ('user', 'Користувач'),
    ('administrator', 'Адміністратор');
