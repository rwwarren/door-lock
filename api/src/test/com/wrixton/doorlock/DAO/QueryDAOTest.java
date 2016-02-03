package com.wrixton.doorlock.DAO;

import com.codahale.metrics.MetricRegistry;
import com.google.common.base.Charsets;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.java8.jdbi.DBIFactory;
import io.dropwizard.setup.Environment;
import org.flywaydb.core.Flyway;
import org.junit.AfterClass;
import org.junit.BeforeClass;
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
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.in;
import static org.hamcrest.Matchers.not;
import static org.hamcrest.Matchers.notNullValue;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

public class QueryDAOTest {

    private static QueryDAO queryDAO;

    @BeforeClass
    public static void setUp() {
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
//        dataSourceFactory.setUser("read");
        dataSourceFactory.setPassword("PASSWORD");
        final DBI jdbi = factory.build(environment, dataSourceFactory, "postgres");
        queryDAO = jdbi.onDemand(QueryDAO.class);
        tearDown();
        prepareTestSql("test_setup.sql");
    }

    @AfterClass
    public static void tearDown() {
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
        DoorlockUserLoginCheck expected = new DoorlockUserLoginCheck("249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9",
                "testing", "test", true);
        assertEquals(expected, loginCheck);
    }

    @Test
    public void testGetUserInfo() throws Exception {
        DoorlockUser user = queryDAO.getUserInfo("test");
        assertThat(user, notNullValue());
        String uuidString = "249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9";
        DoorlockUser doorlockUser = new DoorlockUser(UUID.fromString(uuidString),
                "testing", "test", "asdf@s.com", 12l, "21", true);
        assertEquals(doorlockUser, user);
    }

    @Test
    public void testGetAllAdmins() throws Exception {
        List<BasicDoorlockUser> allAdmins = queryDAO.getAllAdmins();
        assertNotNull(allAdmins);
    }

    @Test
    public void testGetAllActiveUsers() throws Exception {
        List<BasicDoorlockUser> allActiveUsers = queryDAO.getAllActiveUsers();
        assertNotNull(allActiveUsers);
    }

    @Test
    public void testGetAllInactiveUsers() throws Exception {
        List<BasicDoorlockUser> allInactiveUsers = queryDAO.getAllInactiveUsers();
        assertNotNull(allInactiveUsers);
    }

    @Test
    public void testRegisterUser() throws Exception {
        String name = "asdf";
        String username = "ASDFFF";
        String email = "asdf@g.c";
        long authyID = 1234l;
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
        long authyID = 4312l;
        String cardID = "234";
        boolean isAdmin = false;
        String uuidString = "249a61eb-3b76-49ed-a2b7-8e70aa3cc7d9";
        DoorlockUser user = new DoorlockUser(UUID.fromString(uuidString), name, username, email, authyID, cardID, isAdmin);
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
        int rowsUpdated = queryDAO.forgotPassword(1, "reset-url");
        assertNotNull(rowsUpdated);
        assertThat(rowsUpdated, equalTo(1));
    }

    @Test
    public void testResetPassword() throws Exception {
        String username = "updateuser";
        int rowsUpdated = queryDAO.resetPassword(username, "test");
        assertNotNull(rowsUpdated);
        assertThat(rowsUpdated, equalTo(1));
        DoorlockUserLoginCheck doorlockUserLoginCheck = queryDAO.loginUser(username, "test");
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
    public void testCheckResetUrl(){
        long userId = queryDAO.checkResetUrl("reset-url-first");
        assertNotNull(userId);
        assertThat(userId, equalTo(1l));
    }

    @Test
    public void testDeactivateResetUrl(){
        int updatedRows = queryDAO.deactivateResetUrl("reset-url-second");
        assertNotNull(updatedRows);
        assertThat(updatedRows, equalTo(1));
    }
}
