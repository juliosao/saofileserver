#!/bin/bash

mysql <<EOF
DROP DATABASE IF EXISTS saofileserver;
CREATE DATABASE saofileserver CHARSET=latin1;
USE saofileserver;
GRANT DELETE,UPDATE,INSERT,SELECT ON saofileserver.* TO saofileserver@localhost IDENTIFIED BY 'saofileserver' WITH GRANT OPTION;

CREATE TABLE users (
name VARCHAR(64) PRIMARY KEY NOT NULL,
auth VARCHAR(256),
session VARCHAR(256),
mail VARCHAR(256)
);

CREATE TABLE groups (
name VARCHAR(64) PRIMARY KEY NOT NULL UNIQUE
);

CREATE TABLE user2groups (
user VARCHAR(64) NOT NULL,
grp VARCHAR(64) NOT NULL,
PRIMARY KEY (user,grp),
CONSTRAINT user2groups_ibfk_1 FOREIGN KEY (user) REFERENCES users(name) ON DELETE CASCADE,
CONSTRAINT user2groups_ibfk_2 FOREIGN KEY (grp) REFERENCES groups(name) ON DELETE CASCADE
);

CREATE TABLE bundles (
bundle VARCHAR(64) PRIMARY KEY,
path VARCHAR(255),
enabled BOOLEAN
);

INSERT INTO groups (name) VALUES ('admin'),('users');
INSERT INTO bundles  (bundle,path,enabled) VALUES ('player','bundles/player',1);
EOF