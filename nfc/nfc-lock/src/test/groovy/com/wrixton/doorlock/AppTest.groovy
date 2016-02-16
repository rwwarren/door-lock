package com.wrixton.doorlock

import groovyx.net.http.HttpResponseException
import org.junit.Test;

public class AppTest {

    @Test(expected = HttpResponseException.class)
    public void testChangeLockInvalidUID(){
        App app = new App()
        app.changeLock("asdf")
    }

    @Test
    public void testChangeLockValidUID(){
        App app = new App()
        app.changeLock("12FF6FCD")
    }

}
