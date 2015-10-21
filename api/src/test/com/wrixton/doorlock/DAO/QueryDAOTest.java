package com.wrixton.doorlock.DAO;

import com.codahale.metrics.MetricRegistry;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.java8.jdbi.DBIFactory;
import io.dropwizard.setup.Environment;
import junit.framework.TestCase;
import org.junit.BeforeClass;
import org.junit.Test;
import org.skife.jdbi.v2.DBI;
import org.skife.jdbi.v2.sqlobject.Bind;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.notNullValue;
import static org.hamcrest.Matchers.nullValue;

public class QueryDAOTest {

    private static QueryDAO queryDAO;

    @BeforeClass
    public static void setUp(){
        final DBIFactory factory = new DBIFactory();
        Environment environment = new Environment("dev", null, null, new MetricRegistry(), null);
        DataSourceFactory dataSourceFactory = new DataSourceFactory();
        dataSourceFactory.setDriverClass("org.postgresql.Driver");
        dataSourceFactory.getProperties().put("stringtype", "unspecified");
        dataSourceFactory.getProperties().put("charSet", "UTF-8");
        dataSourceFactory.setUrl("jdbc:postgresql://localhost:5432/application_data");
        dataSourceFactory.setUser("read");
        dataSourceFactory.setPassword("PASSWORD");
        final DBI jdbi = factory.build(environment, dataSourceFactory, "postgres");
        queryDAO = jdbi.onDemand(QueryDAO.class);
    }
    @Test
    public void testgetUserInfo() throws Exception {
        DoorlockUser user = queryDAO.getUserInfo("test");
        assertThat(user, notNullValue());
        assertThat(user.getUserID(), equalTo("249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9"));
        assertThat(user.getAuthyID(), nullValue());
        assertThat(user.getCardID(), nullValue());
        assertThat(user.getEmail(), equalTo("asdf"));
        assertThat(user.getName(), equalTo("test"));
        assertThat(user.getUsername(), equalTo("test"));
        assertThat(user.isAdmin(), equalTo(true));
    }
}