package com.wrixton.doorlock

import groovyx.net.http.HttpResponseException
import org.junit.Test;

/**
 * Unit test for simple App.
 */
public class AppTest {
    /**
     * Create the test case
     *
     * @param testName name of the test case
     */
//    public AppTest( String testName ) {
//        super( testName );
//    }

    /**
     * @return the suite of tests being tested
     */
//    public static Test suite() {
//        return new TestSuite( AppTest.class );
//    }

    /**
     * Rigourous Test :-)
     */
//    public void testApp() {
//        assertTrue( true );
//    }

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
