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
)engine=InnoDB COMMENT='テーブル入れ替え用のキュー';

CREATE TABLE queue02
(
    id         bigint   NOT NULL AUTO_INCREMENT,
    job_id     int unsigned     NOT NULL COMMENT 'ジョブの種類',
    status int unsigned NOT NULL DEFAULT '0' COMMENT '0=未処理 1=処理中',
    locker_uuid1 varchar(36),
    created_at datetime NOT NULL,
    updated_at datetime NOT NULL,
    PRIMARY KEY (id),
    KEY job_id(job_id, status, locker_uuid1)
)engine=InnoDB COMMENT='一般的なキュー';
