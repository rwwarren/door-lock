package com.wrixton.doorlock.DAO;

import com.codahale.metrics.MetricRegistry;
import com.google.common.base.Charsets;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.jdbi.DBIFactory;
import io.dropwizard.setup.Environment;
import org.apache.commons.lang3.RandomStringUtils;
import org.flywaydb.core.Flyway;
import org.junit.After;
import org.junit.Before;
import org.junit.Test;
import org.skife.jdbi.v2.DBI;

import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.util.List;
import java.util.UUID;
import java.util.stream.Collectors;

import static com.google.common.io.Resources.getResource;
import static com.google.common.io.Resources.readLines;
import static com.wrixton.doorlock.ConfigurationMethods.saltPassword;
import static junit.framework.TestCase.assertNull;
import static junit.framework.TestCase.assertTrue;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.Matchers.notNullValue;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

public class QueryDAOTest {

    private static QueryDAO queryDAO;
    private final String USER_UUID_STRING = "249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9";
    private UUID USER_UUID = UUID.fromString(USER_UUID_STRING);

    @Before
    public void setUp() {
        //add flyway bundle https://github.com/dropwizard/dropwizard-flyway
        Flyway flyway = new Flyway();
        flyway.setDataSource("jdbc:postgresql://localhost:5432/test_application_data", "db_builder", "PASSWORD");
        flyway.setSchemas("doorlock");
        flyway.migrate();
        final DBIFactory factory = new DBIFactory();
        Environment environment = new Environment("dev", null, null, new MetricRegistry(), null);
        DataSourceFactory dataSourceFactory = new DataSourceFactory();
        dataSourceFactory.setDriverClass("org.postgresql.Driver");
        dataSourceFactory.getProperties().put("stringtype", "unspecified");
        dataSourceFactory.getProperties().put("charSet", "UTF-8");
        dataSourceFactory.setUrl("jdbc:postgresql://localhost:5432/test_application_data");
        dataSourceFactory.setUser("write");
        dataSourceFactory.setPassword("PASSWORD");
        dataSourceFactory.setMaxSize(50);
        final DBI jdbi = factory.build(environment, dataSourceFactory, "postgres");
        queryDAO = jdbi.onDemand(QueryDAO.class);
        tearDown();
        prepareTestSql("test_setup.sql");
    }

    @After
    public void tearDown() {
        prepareTestSql("test_teardown.sql");
    }

    private static void prepareTestSql(String resourceName) {
        try {
            Connection connection = DriverManager.getConnection("jdbc:postgresql://localhost:5432/test_application_data", "db_builder", "PASSWORD");
            URL resource = getResource("sql/" + resourceName);
            List<String> strings = readLines(resource, Charsets.UTF_8);
            String teardown = String.join("\n", strings);
            PreparedStatement ps = connection.prepareStatement(teardown);
            ps.execute();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    @Test
    public void testLoginUser() throws Exception {
        String password = saltPassword("test", "password");
        DoorlockUserLoginCheck loginCheck = queryDAO.loginUser("test", password);
        assertNotNull(loginCheck);
        DoorlockUserLoginCheck expected = new DoorlockUserLoginCheck(USER_UUID_STRING,
                "testing", "test", true);
        assertEquals(expected, loginCheck);
    }

    @Test
    public void testGetUserInfo() throws Exception {
        DoorlockUser user = queryDAO.getUserInfo("test");
        assertThat(user, notNullValue());
        DoorlockUser doorlockUser = new DoorlockUser(USER_UUID,
                "testing", "test", "asdf@s.com", 12L, "21", true);
        assertEquals(doorlockUser, user);
    }

    @Test
    public void testGetAllAdmins() throws Exception {
        List<BasicDoorlockUser> allAdmins = queryDAO.getAllAdmins("test");
        assertNotNull(allAdmins);
        assertEquals(2, allAdmins.size());
    }

    @Test
    public void testGetAllActiveUsers() throws Exception {
        List<BasicDoorlockUser> allActiveUsers = queryDAO.getAllActiveUsers();
        assertNotNull(allActiveUsers);
        assertTrue(allActiveUsers.isEmpty());
    }

    @Test
    public void testGetAllInactiveUsers() throws Exception {
        List<BasicDoorlockUser> allInactiveUsers = queryDAO.getAllInactiveUsers();
        assertNotNull(allInactiveUsers);
        assertTrue(allInactiveUsers.isEmpty());
    }

    @Test
    public void testRegisterUser() throws Exception {
        String name = "asdf";
        String username = "ASDFFF";
        String email = "asdf@g.c";
        long authyID = 1234L;
        String cardID = "12345";
        boolean isAdmin = true;
        DoorlockUser user = new DoorlockUser(null, name, username, email, authyID, cardID, isAdmin);
        int amountChanged = queryDAO.registerUser(name, username, "testing", email, authyID, cardID, isAdmin);
        assertNotNull(amountChanged);
        assertThat(amountChanged, equalTo(1));
        DoorlockUser insertedUser = queryDAO.getUserInfo(username);
        assertNotNull(insertedUser);
        assertNotNull(insertedUser.getUserID());
        assertThat(insertedUser.getUserID(), equalTo(insertedUser.getUserID()));
        assertThat(insertedUser.isAdmin(), equalTo(user.isAdmin()));
        assertThat(insertedUser.getUsername(), equalTo(user.getUsername()));
        assertThat(insertedUser.getName(), equalTo(user.getName()));
        assertThat(insertedUser.getAuthyID(), equalTo(user.getAuthyID()));
        assertThat(insertedUser.getCardID(), equalTo(user.getCardID()));
    }

    @Test
    public void testUpdateCurrentUser() throws Exception {
        String name = "updatecurrentuser";
        String username = "updatecurrentusername";
        String email = "fdsa@g.c";
        long authyID = 4312L;
        String cardID = "234";
        boolean isAdmin = false;
        DoorlockUser user = new DoorlockUser(USER_UUID, name, username, email, authyID, cardID, isAdmin);
        int updatedRows = queryDAO.updateCurrentUser(name, email, authyID, cardID, isAdmin, username, "");
        assertNotNull(updatedRows);
        assertThat(updatedRows, equalTo(1));
        DoorlockUser insertedUser = queryDAO.getUserInfo(username);
        assertThat(insertedUser.isAdmin(), equalTo(user.isAdmin()));
        assertThat(insertedUser.getUsername(), equalTo(user.getUsername()));
        assertThat(insertedUser.getName(), equalTo(user.getName()));
        assertThat(insertedUser.getAuthyID(), equalTo(user.getAuthyID()));
        assertThat(insertedUser.getCardID(), equalTo(user.getCardID()));
    }

    @Test
    public void testUpdateOtherUser() throws Exception {
        String username = "updateuser";
        DoorlockUser initialUser = queryDAO.getUserInfo(username);
        int updatedRows = queryDAO.updateOtherUser(initialUser.getUserID(), false, false);
        assertNotNull(updatedRows);
        assertThat(updatedRows, equalTo(1));
        DoorlockUser updatedUser = queryDAO.getUserInfo(username);
        assertNotNull(updatedUser);
        assertThat(updatedUser, not(equalTo(initialUser)));
        assertThat(updatedUser.getUsername(), equalTo(username));
        assertThat(updatedUser.isAdmin(), equalTo(false));
        List<BasicDoorlockUser> allInactiveUsers = queryDAO.getAllInactiveUsers();
        allInactiveUsers.stream()
                .filter(basicDoorlockUser -> basicDoorlockUser.getUsername().equals(username))
                .collect(Collectors.toList());
        assertThat(allInactiveUsers.size(), equalTo(1));
    }

    @Test
    public void testForgotPassword() throws Exception {
        //make user inactive as well
        //make db trigger to mark user inactive
        //TODO change contains or something to make sure that it Contains a username not equals a user
        String random = RandomStringUtils.random(128, true, true);
        System.out.println(random);
        int rowsUpdated = queryDAO.forgotPassword(1, "reset-url");
        assertNotNull(rowsUpdated);
        assertThat(rowsUpdated, equalTo(1));
    }

    @Test
    public void testFindUserIdForName() {
        {
            UserIDInfo userFullId = queryDAO.findUserIdForName("test");
            assertEquals(new UserIDInfo(1, USER_UUID, true), userFullId);
        }
        {
            UserIDInfo userIdInfoForName = queryDAO.findUserIdForName("test-my-username-not-exist");
            assertNull(userIdInfoForName);
        }
    }

    @Test
    public void testResetPassword() throws Exception {
        String username = "updateuser";
        String password = "test";
        int rowsUpdated = queryDAO.resetPassword(username, password);
        assertNotNull(rowsUpdated);
        assertThat(rowsUpdated, equalTo(1));
        DoorlockUserLoginCheck doorlockUserLoginCheck = queryDAO.loginUser(username, password);
        assertNotNull(doorlockUserLoginCheck);
        assertThat(doorlockUserLoginCheck.getUsername(), equalTo(username));
    }

    @Test
    public void testChangeUserStatus() throws Exception {
        String username = "updateuser";
        int rowsUpdated = queryDAO.changeUserStatus(username, false);
        assertNotNull(rowsUpdated);
        assertThat(rowsUpdated, equalTo(1));
        List<BasicDoorlockUser> allInactiveUsers = queryDAO.getAllInactiveUsers();
        List<BasicDoorlockUser> myUser = allInactiveUsers.stream()
                .filter(basicDoorlockUser -> basicDoorlockUser.getUsername().equals(username))
                .collect(Collectors.toList());
        assertThat(myUser.size(), equalTo(1));
    }

    @Test
    public void testCheckResetUrl() {
        {
            Long userId = queryDAO.checkResetUrl("reset-url-first");
            assertNotNull(userId);
            assertThat(userId, equalTo(1L));
        }
        {
            Long userId = queryDAO.checkResetUrl("some-fake-reset-url");
            assertNull(userId);
        }
    }

    @Test
    public void testDeactivateResetUrl() {
        int updatedRows = queryDAO.deactivateResetUrl("reset-url-second");
        assertNotNull(updatedRows);
        assertThat(updatedRows, equalTo(1));
    }
    
}
