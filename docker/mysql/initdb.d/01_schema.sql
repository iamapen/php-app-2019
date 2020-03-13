CREATE TABLE users
(
    id         int         NOT NULL AUTO_INCREMENT,
    first_name varchar(30) NOT NULL,
    last_name  varchar(30) NOT NULL,
    gender     varchar(1) COMMENT 'm=男,f=女',
    PRIMARY KEY (id)
);

CREATE TABLE queue01
(
    id         bigint   NOT NULL AUTO_INCREMENT,
    job_id     int      NOT NULL,
    created_at datetime NOT NULL,
    PRIMARY KEY (id)
);
