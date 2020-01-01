CREATE TABLE lists (
    id smallint unsigned not null primary key auto_increment,
    name varchar(255) not null
);

CREATE TABLE items (
    `id` smallint unsigned not null primary key auto_increment,
    `listId` smallint unsigned not null,
    `name` varchar(255) not null,
    `rate` smallint unsigned not null,
    `status` tinyint(1) not null default 0,
    `time` datetime not null,
    `modified` datetime null
);

INSERT INTO `lists`(name) values('Default list');
INSERT INTO `items`(`listId`, `name`, `rate`, `time`) values(1, 'Item', 1, NOW());
