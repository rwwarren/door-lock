package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisFactory;
import com.fasterxml.jackson.annotation.JsonProperty;
import io.dropwizard.Configuration;
//import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.db.DataSourceFactory;
import io.dropwizard.db.PooledDataSourceFactory;
import org.hibernate.validator.constraints.NotEmpty;

import javax.validation.Valid;
import javax.validation.constraints.NotNull;
//import io.federecio.dropwizard.swagger.SwaggerBundleConfiguration;

public class DoorlockApiAppConfiguration extends Configuration {

//    @JsonProperty("swagger")
//    public SwaggerBundleConfiguration swaggerBundleConfiguration;

//    @NotEmpty
//    @JsonProperty
//    private String defaultName = "swagger-sample";
//
//    public String getDefaultName() {
//        return defaultName;
//    }

    @JsonProperty("redis")
    public JedisFactory jedisFactory;

    public JedisFactory getJedisFactory(){
        return jedisFactory;
    }

    @Valid
    @NotNull
    @JsonProperty("database")
    private DataSourceFactory database = new DataSourceFactory();

    public PooledDataSourceFactory getDataSourceFactory() {
        return database;
    }

}
