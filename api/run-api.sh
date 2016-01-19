#!/usr/bin/env bash

mvn clean install
java -jar ./target/api-lock-1.0-SNAPSHOT.jar server drop.yml