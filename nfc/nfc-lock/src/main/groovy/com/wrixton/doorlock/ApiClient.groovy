package com.wrixton.doorlock

import groovyx.net.http.RESTClient

//import org.springframework.beans.factory.annotation.Value
//import org.springframework.context.annotation.Bean
//import org.springframework.context.annotation.Configuration
//import org.springframework.context.annotation.PropertySource
import static groovyx.net.http.ContentType.URLENC

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
        def myheaders = ["${apiKeyName}": "test", sid: 'testing']
        def response = restClient.post(
                headers: myheaders,
                body: [ uid: "${uid}"],
                path: '/Lock',
                requestContentType : URLENC,
        )

        println response.responseData
        return response.responseData
    }

    def unlock(def uid){
        def myheaders = ["${apiKeyName}": "test", sid: 'testing']
        def response = restClient.post(
                headers: myheaders,
                body: [uid: "${uid}"],
                path: '/Unlock',
                requestContentType : URLENC,
        )
        println response.responseData
        return response.responseData
    }

    def getUrl(){
        return url
    }


}
