package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisBundle;
import com.bendb.dropwizard.redis.JedisFactory;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.wrixton.doorlock.DAO.QueryDAO;
import com.wrixton.doorlock.resources.DoorlockApiAppResource;
import io.dropwizard.Application;
import io.dropwizard.assets.AssetsBundle;
import io.dropwizard.java8.Java8Bundle;
import io.dropwizard.java8.jdbi.DBIFactory;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;
import io.swagger.jaxrs.config.BeanConfig;
import io.swagger.jaxrs.listing.ApiListingResource;
import io.swagger.jaxrs.listing.SwaggerSerializers;
import org.eclipse.jetty.servlets.CrossOriginFilter;
import org.skife.jdbi.v2.DBI;

import javax.servlet.DispatcherType;
import javax.servlet.FilterRegistration;
import java.util.EnumSet;
//import io.swagger.jaxrs.config.BeanConfig;
//import io.federecio.dropwizard.swagger.SwaggerBundle;
//import io.federecio.dropwizard.swagger.SwaggerBundleConfiguration;
//import io.swagger.jaxrs.config.BeanConfig;

public class DoorlockApiApp extends Application<DoorlockApiAppConfiguration> {

    public static void main(String[] args) {
        try {
            new DoorlockApiApp().run(args);
        } catch (Exception e) {
            System.out.println(e.toString());
        }
    }

    @Override
    public String getName() {
        return "doorlock-api-app";
    }

    @Override
    public void initialize(Bootstrap<DoorlockApiAppConfiguration> bootstrap) {
        // nothing to do yet
//        bootstrap.addBundle(new SwaggerBundle<DoorlockApiAppConfiguration>() {
//            @Override
//            protected SwaggerBundleConfiguration getSwaggerBundleConfiguration(DoorlockApiAppConfiguration configuration) {
//                return configuration.swaggerBundleConfiguration;
//            }
//        });
        bootstrap.addBundle(new Java8Bundle());
//        bootstrap.addBundle(new JDBIBundle());
        bootstrap.addBundle(new JedisBundle<DoorlockApiAppConfiguration>() {
            @Override
            public JedisFactory getJedisFactory(DoorlockApiAppConfiguration configuration) {
                return configuration.getJedisFactory();
            }
        });
//        bootstrap.addBundle(new AssetsBundle());
//        bootstrap.addBundle(new AssetsBundle("/assets/css", "/css"));
        bootstrap.addBundle(new AssetsBundle("/assets/css", "/css", "index.htm", "/css"));
        bootstrap.addBundle(new AssetsBundle("/assets/lib", "/lib", "index.htm", "/lib"));
//        bootstrap.addBundle(new AssetsBundle("/assets/lib", "/lib"));
//        bootstrap.addBundle(new AssetsBundle("/assets"));
//        bootstrap.addBundle(new AssetsBundle("/assets", "/css", "index.html"));
//        bootstrap.addBundle(new SwaggerSerializers());
    }

    @Override
    public void run(DoorlockApiAppConfiguration doorlockApiAppConfiguration, Environment environment) throws Exception {

        final FilterRegistration.Dynamic cors =
                environment.servlets().addFilter("CORS", CrossOriginFilter.class);
        environment.jersey().register(new ApiListingResource());

        // Configure CORS parameters
        cors.setInitParameter("allowedOrigins", "*");
        cors.setInitParameter("allowedHeaders", "X-Requested-With,Content-Type,Accept,Origin");
        cors.setInitParameter("allowedMethods", "OPTIONS,GET,PUT,POST,DELETE,HEAD");

        // Add URL mapping
        cors.addMappingForUrlPatterns(EnumSet.allOf(DispatcherType.class), true, "/*");

        final DBIFactory factory = new DBIFactory();
        final DBI jdbi = factory.build(environment, doorlockApiAppConfiguration.getDataSourceFactory(), "mysql");
//        final DBI jdbi = factory.build(environment, doorlockApiAppConfiguration.getDataSourceFactory(), "postgres");
        final QueryDAO personDAO = jdbi.onDemand(QueryDAO.class);
        final DoorlockApiAppResource resource = new DoorlockApiAppResource(personDAO);
        environment.jersey().register(resource);

        final healthcheck healthCheck =
                new healthcheck(doorlockApiAppConfiguration.toString());
        environment.healthChecks().register("template", healthCheck);


//        environment.getObjectMapper().setSerializationInclusion(JsonInclude.Include.NON_NULL);


        BeanConfig config = new BeanConfig();
        config.setTitle("Doorlock Api App");
        config.setVersion("1.0.0");
//        config.setResourcePackage("com.wrixton.doorlock.resources.DoorlockApiAppResource");
        config.setResourcePackage("com.wrixton.doorlock.resources");
        config.setScan(true);
        config.setHost("localhost");
        config.setBasePath("/");
        config.setSchemes(new String[]{"http"});
        environment.jersey().register(config);

        environment.jersey().setUrlPattern("/css/*");
        environment.jersey().setUrlPattern("/lib/*");

//        FilterRegistration.Dynamic filter = environment.servlets().addFilter("CORSFilter", CrossOriginFilter.class);
//        filter.setInitParameter("allowedOrigins", "*");
//        filter.setInitParameter("allowedHeaders", "X-Requested-With,Content-Type,Accept,Origin");
//        filter.setInitParameter("allowedMethods", "OPTIONS,GET,PUT,POST,DELETE,HEAD");
        // Add URL mapping
//        filter.addMappingForUrlPatterns(EnumSet.allOf(DispatcherType.class), true, "/*");
//        environment.addFilter(CrossOriginFilter.class, "/*")
//                .setInitParam("allowedOrigins", "*")
//                .setInitParam("allowedHeaders", "X-Requested-With,Content-Type,Accept,Origin")
//                .setInitParam("allowedMethods", "OPTIONS,GET,PUT,POST,DELETE,HEAD");

//        environment.jersey().setUrlPattern("/application/*");

//        environment.servlets().addServlet(assetsName, createServlet()).addMapping(uriPath + '*');

    }
}
