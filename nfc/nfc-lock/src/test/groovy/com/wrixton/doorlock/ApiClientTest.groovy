package com.wrixton.doorlock

import groovyx.net.http.HttpResponseException
import org.junit.Before
import org.junit.Test

import static junit.framework.TestCase.assertTrue
import static org.hamcrest.MatcherAssert.assertThat
import static org.hamcrest.core.IsEqual.equalTo
import static org.hamcrest.core.IsNull.notNullValue

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

    @Test(expected = HttpResponseException.class)
    public void getLockNoUID(){
        try{
            def response = apiClient.lock("")
        } catch(HttpResponseException e){
            assertThat("Response should not be null", e.response, notNullValue())
            assertThat("Response should not be null", e.response.responseData.success, equalTo("0"))
            throw e
        }
    }

    @Test(expected = HttpResponseException.class)
    public void getUnlockNoUID(){
        try{
            def response = apiClient.unlock("")
        } catch(HttpResponseException e){
            assertThat("Response should not be null", e.response, notNullValue())
            assertThat("Response should not be null", e.response.responseData.success, equalTo("0"))
            throw e
        }
    }

    @Test
    public void getLock(){
        def response = apiClient.lock("12FF6FCD")
        assertThat("Response should not be null", response, notNullValue())
        assertTrue("Response data should be success", response.success as Boolean)
        assertThat("Response data should be locked", response."Locked Door", equalTo("Success"))
    }

    @Test
    public void getUnlock(){
        def response = apiClient.unlock("12FF6FCD")
        assertThat("Response should not be null", response, notNullValue())
        assertTrue("Response data should be success", response."success" as Boolean)
        assertThat("Response data should be locked", response."Unlocked Door", equalTo("Success"))
    }

    @Test
    public void getLockStatus(){
        def response = apiClient.lockStatus("12FF6FCD")
        assertThat("Response should not be null", response, notNullValue())
        //TODO change this
        assertThat("Response should be locked", response, equalTo(LOCK_STATUS.LOCKED))
    }
}
