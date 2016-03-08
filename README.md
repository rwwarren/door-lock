# [PiLock] (http://doorlock.war.re/) - Electronic Deadbolt System

[![Build Status](https://travis-ci.org/rwwarren/door-lock.png?branch=master)](https://travis-ci.org/rwwarren/door-lock)
[![Coverage Status](https://img.shields.io/coveralls/rwwarren/door-lock.svg)](https://coveralls.io/r/rwwarren/door-lock)
[![Code Climate](https://codeclimate.com/github/rwwarren/door-lock/badges/gpa.svg)](https://codeclimate.com/github/rwwarren/door-lock)

TODO:
Using gulp
setting up api -> dropwizard 
Remove all files that are no longer needed (many in the base dir)
setup gulp script

Things to install
nodejs
reactjs
react native
jquery
maven

to add:
jest
relay
graphql
swagger for api?? finish up swagger ui -> ui not working dropwizard 
flyway for db migration
fix the cross domain origin error in nodejs app
add 'corsproxy' to the package json and when running
authy to api

remove legacy php from mobile
rewrite mobile to use web optimzied
fix/investigate bug if you logout but page not refresh you can still use the site
newrelic -> fix naming

Api ->json and post variables
Mobile -> web (move to web and use the same thing) & application
Web
NFC
Servo -> use java api client

##Api
[API](api/)
- Java 8
- dropwizard 0.9.2
- dropwizard java 8
- maven
- jdbi -> postgresql (9.4.4) using jdbi dropwizard
- swagger 1.5.4 api documentation

##Web (+ mobile optimized)
[Web](web/)
- nodejs
- reactjs

##NFC
[NFC](nfc/)
- Java 8

##Servo
[Servo](servo/)
- Moves the deadbolt using a servo
- python

##Mobile Application
[Mobile](mobile/)
- react native




