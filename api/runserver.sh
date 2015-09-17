#!/usr/bin/env bash

 cd ./src/root && hhvm -m server -vHack.Lang.LookForTypechecker=0 -c ../properties/server.ini -vAdminServer.Port=9001
# hhvm -m server -vHack.Lang.LookForTypechecker=0 -c ./src/properties/server.ini -vAdminServer.Port=9001
# hhvm -m server -p 8080 -vHack.Lang.LookForTypechecker=0 -c ./src/properties/server.ini