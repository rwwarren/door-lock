# [PiLock] (http://doorlock.war.re/) - Electronic Deadbolt System

[![Build Status](https://travis-ci.org/rwwarren/door-lock.png?branch=master)](https://travis-ci.org/rwwarren/door-lock)
[![Coverage Status](https://img.shields.io/coveralls/rwwarren/door-lock.svg)](https://coveralls.io/r/rwwarren/door-lock)

TODO:
Using gulp
setting up api -> dropwizard 
Remove all files that are no longer needed (many in the base dir)

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
make db trigger for uuid after insert
fix the cross domain origin error in nodejs app
add 'corsproxy' to the package json and when running
authy to api

remove legacy php from mobile
rewrite mobile to use web optimzied
fix/investigate bug if you logout but page not refresh you can still use the site
newrelic


Api ->json and post variables
Mobile -> web (move to web and use the same thing) & application
Web
NFC
Servo -> use java api client



##Api
-Java 8
[API](api/README.md)
- dropwizard .09-rc
- dropwizard java 8
- maven
- jdbi -> postgresql using jdbi dropwizard
- swagger 1.5.3 api documentation

##Web
[Web](web/README.md)
- nodejs
- reactjs

##NFC/Servo
[NFC](nfc/README.md)
- java

##Mobile Application
[Mobile](mobile/README.md)
- react native
