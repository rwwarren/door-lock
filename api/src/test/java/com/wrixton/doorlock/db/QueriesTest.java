package com.wrixton.doorlock.db;

import org.junit.Test;

import static org.junit.Assert.assertNotNull;

public class QueriesTest {

    @Test
    public void testQueries(){
        try {
            Queries queries = new Queries();
            String name = queries.getName();
            System.out.println(name.toString());
            assertNotNull(name);
        } catch (Exception e) {
            e.printStackTrace();
        }

    }


}