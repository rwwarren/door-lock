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

import static com.google.common.io.Resources.getResource;
import static com.google.common.io.Resources.readLines;
import static com.wrixton.doorlock.ConfigurationMethods.saltPassword;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
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
//        dataSourceFactory.setUrl("jdbc:postgresql://localhost:5432/application_data");
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
        String password = saltPassword("test", "test");
        DoorlockUserLoginCheck loginCheck = queryDAO.loginUser("test", password);
        assertNotNull(loginCheck);
        DoorlockUserLoginCheck expected = new DoorlockUserLoginCheck("249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9",
                "test", "test", true);
        assertEquals(expected, loginCheck);
    }

    @Test
    public void testGetUserInfo() throws Exception {
        DoorlockUser user = queryDAO.getUserInfo("test");
        assertThat(user, notNullValue());
        DoorlockUser doorlockUser = new DoorlockUser("249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9",
                "test", "test", "asdf", null, null, true);
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
        DoorlockUser user = new DoorlockUser("", "asdf", "ASDFFF", "asdf@g.c", 1234l, "12345", true);
        int amountChanged = queryDAO.registerUser("asdf", "ASDFFF", "testing", "asdf@g.c", 1234l, "12345", true);
        assertNotNull(amountChanged);
        assertThat(amountChanged, equalTo(1));
        DoorlockUser insertedUser = queryDAO.getUserInfo("ASDFFF");
        assertNotNull(insertedUser);
        assertNotNull(insertedUser.getUserID());
        UUID fromStringUUID = UUID.fromString(insertedUser.getUserID());
        String toStringUUID = fromStringUUID.toString();
        assertThat(insertedUser.getUserID(), equalTo(toStringUUID));
        assertThat(insertedUser.isAdmin(), equalTo(user.isAdmin()));
        assertThat(insertedUser.getUsername(), equalTo(user.getUsername()));
        assertThat(insertedUser.getName(), equalTo(user.getName()));
        assertThat(insertedUser.getAuthyID(), equalTo(user.getAuthyID()));
        assertThat(insertedUser.getCardID(), equalTo(user.getCardID()));
    }

    @Test
    public void testUpdateCurrentUser() throws Exception {
//        queryDAO.updateCurrentUser();
    }

    @Test
    public void testUpdateOtherUser() throws Exception {
//        queryDAO.updateOtherUser();
    }

    @Test
    public void testForgotPassword() throws Exception {
        //make user inactive as well
//        queryDAO.forgotPassword();
    }

    @Test
    public void testResetPassword() throws Exception {
//        queryDAO.resetPassword();
    }
}