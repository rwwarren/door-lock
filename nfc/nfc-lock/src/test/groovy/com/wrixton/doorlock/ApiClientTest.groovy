package com.wrixton.doorlock

import groovyx.net.http.HttpResponseException
import org.junit.Before
import org.junit.Test

import static org.hamcrest.MatcherAssert.assertThat
import static org.hamcrest.core.IsEqual.equalTo
import static org.hamcrest.core.IsNull.notNullValue
import static org.mockito.Mockito.when

class ApiClientTest {

    def apiClient
    def apiUrl = "http://api.localhost"
    def apiKey = "testing"
    //TODO get mockito to mock url response for lock?

    @Before
    public void setUp(){
        apiClient = new ApiClient(apiUrl, apiKey)
        assertThat("api client should not be null", apiClient, notNullValue())
    }

    @Test
    public void getUrlTest(){
        def url = apiClient.getUrl()
        assertThat("url should equal $apiUrl", url, equalTo(apiUrl))
    }

    @Test
    public void getApiKeyTest(){
        def key = apiClient.apiKey
        assertThat("url should equal $apiKey", key, equalTo(apiKey))
//        when()
    }

//    @Test(expected = HttpResponseException.class)
//    public void getLogin(){
//        apiClient.setApiKey("")
//        def response = apiClient.login()
//        assertThat("Response should have unauthorized api key", response, notNullValue())
//    }

//    @Test(expected = HttpResponseException.class)
    @Test
    public void getLock(){
        def response = apiClient.lock()
        assertThat("Response should not be null", response, notNullValue())
    }

    @Test
    public void getUnlock(){
        def response = apiClient.unlock()
        assertThat("Response should not be null", response, notNullValue())
    }
}