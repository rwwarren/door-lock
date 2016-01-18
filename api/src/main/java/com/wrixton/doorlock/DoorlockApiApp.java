package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisBundle;
import com.bendb.dropwizard.redis.JedisFactory;
import com.wrixton.doorlock.DAO.QueryDAO;
import com.wrixton.doorlock.resources.DoorlockApiAppResource;
import io.dropwizard.Application;
import io.dropwizard.assets.AssetsBundle;
import io.dropwizard.java8.Java8Bundle;
import io.dropwizard.java8.jdbi.DBIFactory;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;
import io.swagger.config.ScannerFactory;
import io.swagger.jaxrs.config.ReflectiveJaxrsScanner;
import io.swagger.jaxrs.listing.ApiListingResource;
import io.swagger.models.Info;
import io.swagger.models.Swagger;
import org.eclipse.jetty.servlets.CrossOriginFilter;
import org.skife.jdbi.v2.DBI;
import static org.eclipse.jetty.servlets.CrossOriginFilter.*;


import javax.servlet.DispatcherType;
import javax.servlet.FilterRegistration;
import javax.servlet.ServletContextEvent;
import javax.servlet.ServletContextListener;
import java.util.EnumSet;
import java.util.logging.Logger;

public class DoorlockApiApp extends Application<DoorlockApiAppConfiguration> {

    private static final Logger LOG = Logger.getLogger(DoorlockApiApp.class.getName());
    private String VERSION = "0.08";
    private static final String GOOD_ORIGIN = "allowed_host";
    private static final String BAD_ORIGIN = "denied_host";

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
        bootstrap.addBundle(new Java8Bundle());
        bootstrap.addBundle(new JedisBundle<DoorlockApiAppConfiguration>() {
            @Override
            public JedisFactory getJedisFactory(DoorlockApiAppConfiguration configuration) {
                return configuration.getJedisFactory();
            }
        });
        bootstrap.addBundle(new AssetsBundle("/assets/css", "/css", "index.htm", "/css"));
        bootstrap.addBundle(new AssetsBundle("/assets/lib", "/lib", "index.htm", "/lib"));
        bootstrap.addBundle(new AssetsBundle("/assets/fonts", "/fonts", "index.htm", "/fonts"));
        bootstrap.addBundle(new AssetsBundle("/assets/images", "/images", "index.htm", "/images"));
    }

    @Override
    public void run(DoorlockApiAppConfiguration doorlockApiAppConfiguration, Environment environment) throws Exception {

//        FilterRegistration.Dynamic filter = environment.servlets().addFilter("CORSFilter", CrossOriginFilter.class);
//
//        filter.addMappingForUrlPatterns(EnumSet.of(DispatcherType.REQUEST), false, environment.getApplicationContext().getContextPath() + "*");
//        filter.setInitParameter(ALLOWED_METHODS_PARAM, "GET,PUT,POST,OPTIONS");
//        filter.setInitParameter(ALLOWED_ORIGINS_PARAM, GOOD_ORIGIN);
//        filter.setInitParameter(ALLOWED_HEADERS_PARAM, "Origin, Content-Type, Accept");
//        filter.setInitParameter(ALLOW_CREDENTIALS_PARAM, "true");

        environment.jersey().register(new ApiListingResource());

        final DBIFactory factory = new DBIFactory();
        final DBI jdbi = factory.build(environment, doorlockApiAppConfiguration.getDataSourceFactory(), "postgres");
        final QueryDAO queryDAO = jdbi.onDemand(QueryDAO.class);
        final DoorlockApiAppResource resource = new DoorlockApiAppResource(queryDAO);
        environment.jersey().register(resource);

        final Healthcheck healthCheck =
                new Healthcheck(doorlockApiAppConfiguration.getAppName());
        environment.healthChecks().register("template", healthCheck);
        environment.jersey().setUrlPattern("/css/*");
        environment.jersey().setUrlPattern("/lib/*");

        final Swagger swagger = new Swagger();
        swagger.setBasePath("/");
        Info info = new Info();
        info.setTitle("Doorlock api");
        info.setVersion(VERSION);
        swagger.setInfo(info);

        ReflectiveJaxrsScanner reflectiveJaxrsScanner = new ReflectiveJaxrsScanner();
        reflectiveJaxrsScanner.setResourcePackage("com.wrixton.doorlock.resources");
        reflectiveJaxrsScanner.setPrettyPrint(true);
        ScannerFactory.setScanner(reflectiveJaxrsScanner);

        environment.servlets().addServletListeners(new ServletContextListener() {
            @Override
            public void contextInitialized(ServletContextEvent servletContextEvent) {
                servletContextEvent.getServletContext().setAttribute("swagger", swagger);
            }

            @Override
            public void contextDestroyed(ServletContextEvent servletContextEvent) {

            }
        });
        environment.jersey().register(io.swagger.jaxrs.listing.SwaggerSerializers.class);
        environment.jersey().register(io.swagger.jaxrs.listing.ApiListingResource.class);
        LOG.info("Started up!");
    }
}
