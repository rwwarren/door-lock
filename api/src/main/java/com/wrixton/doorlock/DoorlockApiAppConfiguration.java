package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisFactory;
import com.fasterxml.jackson.annotation.JsonProperty;
import io.dropwizard.Configuration;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.db.PooledDataSourceFactory;

import javax.validation.Valid;
import javax.validation.constraints.NotNull;

public class DoorlockApiAppConfiguration extends Configuration {

    @JsonProperty("redis")
    public JedisFactory jedisFactory;

    public JedisFactory getJedisFactory() {
        return jedisFactory;
    }

    @Valid
    @NotNull
    @JsonProperty("database")
    private DataSourceFactory database = new DataSourceFactory();

    public PooledDataSourceFactory getDataSourceFactory() {
        return database;
    }

    @NotNull
    @JsonProperty("app-name")
    private String appName;

    public String getAppName() {
        return appName;
    }
}
