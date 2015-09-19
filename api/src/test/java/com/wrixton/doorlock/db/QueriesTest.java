package com.wrixton.doorlock.db;

import com.wrixton.doorlock.DAO.DoorlockUser;
import org.junit.Test;

import static org.hamcrest.CoreMatchers.equalTo;
import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertThat;

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

    @Test
    public void testQueriesLogin(){
        try {
            DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
            Queries queries = new Queries();
            DoorlockUser login = queries.login(testUser.getUsername(), "password");
            assertNotNull(login);
            assertThat(login, equalTo(testUser));
        } catch (Exception e) {
            e.printStackTrace();
        }

    }


}