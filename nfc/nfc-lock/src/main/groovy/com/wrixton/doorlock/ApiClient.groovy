package com.wrixton.doorlock

class ApiClient{

    //Add spring to inject this
    def private url
    def private apiKey

    public ApiClient(String url, String apiKey){
        this.url = url
        this.apiKey = apiKey
    }

    def login(){
//        def restClient = new RESTClient()


    }

    def logout(){

    }

    def isLoggedin(){

    }

    def isAdmin(){

    }

    def getUserInfo(){

    }

    def lockStatus(){

    }

    def lock(){

    }

    def unlock(){

    }

    def getUrl(){
        return url
    }


}