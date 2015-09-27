package com.wrixton.doorlock.db;

import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import org.junit.Test;

import java.util.List;
import java.util.Map;

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
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        queries.login(null, "password");
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginUsernameEmpty() throws Exception {
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        queries.login("", "password");
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginPasswordNull() throws Exception {
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        queries.login("test", null);
        fail("Should have failed already");
    }

    @Test(expected = IllegalStateException.class)
    public void testQueriesLoginPasswordEmpty() throws Exception {
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        queries.login("test", "");
        fail("Should have failed already");
    }

    @Test
    public void testQueriesLoginNotValidUsername() throws Exception {
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        DoorlockUserLoginCheck user = queries.login("test1", "password");
        assertNull(user);
    }

    @Test
    public void testQueriesLoginNotValidPassword() throws Exception {
        DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("1", "Test", "test", true);
        Queries queries = new Queries();
        DoorlockUserLoginCheck user = queries.login("test", "password1");
        assertNull(user);
    }

    @Test
    public void testQueriesLogin() {
        try {
            DoorlockUserLoginCheck testUser = new DoorlockUserLoginCheck("a64f0604-6270-11e5-9d70-feff819cdc9f", "Test", "test", true);
            Queries queries = new Queries();
            DoorlockUserLoginCheck login = queries.login(testUser.getUsername(), "password");
            assertNotNull(login);
            assertThat(login, equalTo(testUser));
        } catch (Exception e) {
            e.printStackTrace();
            fail("Should not have thrown an error");
        }
    }

    @Test
    public void testQueriesGetAllAdmins() {
        try {
            Queries queries = new Queries();
            List<BasicDoorlockUser> allAdmins = queries.getAllAdmins();
            assertNotNull(allAdmins);
            assertThat(allAdmins.size(), equalTo(1));
        } catch (Exception e) {
            e.printStackTrace();
            fail("Should not have thrown an error");
        }
    }

    @Test
    public void testQueriesGetAllActives() {
        try {
            Queries queries = new Queries();
            List<BasicDoorlockUser> allActives = queries.getAllActiveUsers();
            assertNotNull(allActives);
            assertThat(allActives.size(), equalTo(3));
        } catch (Exception e) {
            e.printStackTrace();
            fail("Should not have thrown an error");
        }
    }

    @Test
    public void testQueriesGetAllInactives() {
        try {
            Queries queries = new Queries();
            List<BasicDoorlockUser> allInactives = queries.getAllInactiveUsers();
            assertNotNull(allInactives);
            assertThat(allInactives.size(), equalTo(1));
        } catch (Exception e) {
            e.printStackTrace();
            fail("Should not have thrown an error");
        }
    }

    @Test
    public void testQueriesGetAll() {
        try {
            Queries queries = new Queries();
            Map<String, List<BasicDoorlockUser>> allUsers = queries.getAllUsers();
            assertNotNull(allUsers);
            assertThat(allUsers.keySet().size(), equalTo(3));

            assertThat(allUsers.get("Admins").size(), equalTo(1));
            assertThat(allUsers.get("Active").size(), equalTo(3));
            assertThat(allUsers.get("Inactive").size(), equalTo(1));

        } catch (Exception e) {
            e.printStackTrace();
            fail("Should not have thrown an error");
        }
    }

}