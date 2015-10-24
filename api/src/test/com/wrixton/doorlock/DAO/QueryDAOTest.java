package com.wrixton.doorlock.DAO;

import com.codahale.metrics.MetricRegistry;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.java8.jdbi.DBIFactory;
import io.dropwizard.setup.Environment;
import org.flywaydb.core.Flyway;
import org.junit.AfterClass;
import org.junit.BeforeClass;
import org.junit.Test;
import org.skife.jdbi.v2.DBI;

import java.util.List;

import static com.wrixton.doorlock.ConfigurationMethods.saltPassword;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.notNullValue;
import static org.junit.Assert.assertEquals;
import static org.junit.Assert.assertNotNull;

public class QueryDAOTest {

    private static QueryDAO queryDAO;

    @BeforeClass
    public static void setUp() {
        //add flyway bundle https://github.com/dropwizard/dropwizard-flyway
//        Flyway flyway = new Flyway();
//        flyway.setSchemas("doorlock");
//        int migrate = flyway.migrate();

        final DBIFactory factory = new DBIFactory();
        Environment environment = new Environment("dev", null, null, new MetricRegistry(), null);
        DataSourceFactory dataSourceFactory = new DataSourceFactory();
        dataSourceFactory.setDriverClass("org.postgresql.Driver");
        dataSourceFactory.getProperties().put("stringtype", "unspecified");
        dataSourceFactory.getProperties().put("charSet", "UTF-8");
        dataSourceFactory.setUrl("jdbc:postgresql://localhost:5432/test_application_data");
//        dataSourceFactory.setUrl("jdbc:postgresql://localhost:5432/application_data");
        dataSourceFactory.setUser("read");
        dataSourceFactory.setPassword("PASSWORD");
        final DBI jdbi = factory.build(environment, dataSourceFactory, "postgres");
        queryDAO = jdbi.onDemand(QueryDAO.class);
    }

    @AfterClass
    public static void tearDown() {

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
//        queryDAO.registerUser();
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