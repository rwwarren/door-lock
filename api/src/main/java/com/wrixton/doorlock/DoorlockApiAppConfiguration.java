package com.wrixton.doorlock;

import com.bendb.dropwizard.redis.JedisFactory;
import com.fasterxml.jackson.annotation.JsonProperty;
import io.dropwizard.Configuration;
import org.hibernate.validator.constraints.NotEmpty;
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

}
