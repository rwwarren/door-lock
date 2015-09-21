package com.wrixton.doorlock.db;

import com.wrixton.doorlock.DAO.DoorlockUser;
import org.junit.Test;

import static org.hamcrest.CoreMatchers.equalTo;
import static org.junit.Assert.*;

public class QueriesTest {

//    @Test
//    public void testQueries() {
//        try {
//            Queries queries = new Queries();
//            String name = queries.getName();
//            System.out.println(name.toString());
//            assertNotNull(name);
//        } catch (Exception e) {
//            e.printStackTrace();
//        }
//
//    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginUsernameNull() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        queries.login(null, "password");
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginUsernameEmpty() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        queries.login("", "password");
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginPasswordNull() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        queries.login("test", null);
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginPasswordEmpty() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        queries.login("test", "");
        fail("Should have failed already");
    }

    @Test
    public void testQueriesLoginNotValidUsername() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        DoorlockUser user = queries.login("test1", "password");
        assertNull(user);
    }

    @Test
    public void testQueriesLoginNotValidPassword() throws Exception {
        DoorlockUser testUser = new DoorlockUser(1, "Test", "test", true);
        Queries queries = new Queries();
        DoorlockUser user = queries.login("test", "password1");
        assertNull(user);
    }

    @Test
    public void testQueriesLogin() {
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