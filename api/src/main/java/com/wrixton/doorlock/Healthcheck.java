package com.wrixton.doorlock;

import com.codahale.metrics.health.HealthCheck;

public class Healthcheck extends HealthCheck {

    private final String appName;

    public Healthcheck(String appName) {
        this.appName = appName;
    }

    @Override
    protected Result check() throws Exception {
        if (!appName.equals("doorlock-api")) {
            return Result.unhealthy("app-name does not match");
        }
        return Result.healthy();
    }
}
