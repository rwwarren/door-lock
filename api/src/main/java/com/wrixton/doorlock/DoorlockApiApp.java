package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisBundle;
import com.bendb.dropwizard.redis.JedisFactory;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.wrixton.doorlock.resources.DoorlockApiAppResource;
import io.dropwizard.Application;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;
import io.swagger.jaxrs.config.BeanConfig;
import io.swagger.jaxrs.listing.ApiListingResource;
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
        bootstrap.addBundle(new JedisBundle<DoorlockApiAppConfiguration>() {
            @Override
            public JedisFactory getJedisFactory(DoorlockApiAppConfiguration configuration) {
                return configuration.getJedisFactory();
            }
        });
    }

    @Override
    public void run(DoorlockApiAppConfiguration doorlockApiAppConfiguration, Environment environment) throws Exception {
        final DoorlockApiAppResource resource = new DoorlockApiAppResource(
        );
        environment.jersey().register(resource);

        final healthcheck healthCheck =
                new healthcheck(doorlockApiAppConfiguration.toString());
        environment.healthChecks().register("template", healthCheck);

//        environment.getObjectMapper().setSerializationInclusion(JsonInclude.Include.NON_NULL);

        environment.jersey().register(new ApiListingResource());

        BeanConfig config = new BeanConfig();
        config.setTitle("Swagger sample app");
        config.setVersion("1.0.0");
        config.setResourcePackage("com.wrixton.doorlock.resources");
        config.setScan(true);
//        environment.jersey().register(config);

    }
}
