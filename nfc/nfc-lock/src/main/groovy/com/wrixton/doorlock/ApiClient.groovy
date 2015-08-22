package com.wrixton.doorlock

import groovyx.net.http.HTTPBuilder
import groovyx.net.http.HttpResponseException

import static groovyx.net.http.ContentType.*
//import org.springframework.beans.factory.annotation.Value
//import org.springframework.context.annotation.Bean
//import org.springframework.context.annotation.Configuration
//import org.springframework.context.annotation.PropertySource
import groovyx.net.http.RESTClient
import static groovyx.net.http.Method.POST

//@ Configuration
//@ PropertySource("classpath:/properties/api.properties")
class ApiClient{

    //Add spring to inject this

//    @ V alue("${api.url}")
    def private url

//    @ V alue("${api.key}")
    def private apiKey
    def private apiKeyName = "X-DoorLock-Api-Key"

    def private restClient

    public ApiClient(String url, String apiKey){
        this.url = url
        this.apiKey = apiKey
        restClient = new RESTClient(url)
    }

    public void setApiKey(String key){
        apiKey = key
    }

//    @Bean
//    public ApiClient(){
//        this(url, apiKey)
//    }

    def boolean isValidUser(){
        return true
    }

    def login() {

    }

    def logout(){

    }

    def isLoggedin(){

    }

    def isAdmin(){

    }

    def getUserInfo(){

    }

    def LOCK_STATUS lockStatus(def uid){
//        return LOCK_STATUS.valueOf("locked")
//        return LOCK_STATUS.UNLOCKED
        return LOCK_STATUS.LOCKED
    }

    def lock(def uid){
//    def lock(def username, def uid){
//        def restClient = new RESTClient()
//        restClient.get(path: '/login')
//        def myheaders = [xnfcapikey: "test", sid: 'testing']
        def myheaders = ["${apiKeyName}": "test", sid: 'testing']
//        def myheaders = ["X-DoorLock-Api-Key": apiKey, sid:'testing']
        def response = restClient.post(
                headers: myheaders,
                body: [ uid: "${uid}"],
//                body: [username: "${username}", uid: "${uid}"],
//                body: [username: 'test', password: 'password'],
                path: '/Lock',
//                contentType: JSON,
                requestContentType : URLENC,
        )

        println response.responseData
        return response.responseData
    }

    def unlock(def uid){
//    def unlock(def username, def uid){
//        def restClient = new RESTClient()
//        restClient.get(path: '/login')
//        def myheaders = [xnfcapikey: "test", sid: 'testing']
        def myheaders = ["${apiKeyName}": "test", sid: 'testing']
//        def myheaders = ["X-DoorLock-Api-Key": apiKey, sid:'testing']
        def response = restClient.post(
                headers: myheaders,
                body: [uid: "${uid}"],
//                body: [username: "${username}", uid: "${uid}"],
//                body: [username: 'test'],
//                body: [username: 'test', password: 'password'],
                path: '/Unlock',
//                contentType: JSON,
                requestContentType : URLENC,
        )
        println response.responseData
        return response.responseData
    }

    def getUrl(){
        return url
    }


}