package com.wrixton.doorlock;

import com.wrixton.doorlock.resources.DoorlockApiAppResource;
import io.dropwizard.Application;
import io.dropwizard.setup.Bootstrap;
import io.dropwizard.setup.Environment;

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
    }

    @Override
    public void run(DoorlockApiAppConfiguration doorlockApiAppConfiguration, Environment environment) throws Exception {
        final DoorlockApiAppResource resource = new DoorlockApiAppResource(
        );
        environment.jersey().register(resource);

        final healthcheck healthCheck =
                new healthcheck(doorlockApiAppConfiguration.toString());
        environment.healthChecks().register("template", healthCheck);

    }
}
