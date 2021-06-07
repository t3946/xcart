#!/usr/bin/env bash

mysql -u root -proot xcart_k < "/docker-entrypoint-initdb.d/dump.sql"
