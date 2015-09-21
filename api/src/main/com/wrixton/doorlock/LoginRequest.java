package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;

//@JsonIgnoreProperties(ignoreUnknown = true)
public class LoginRequest {

    @NotNull
    private final String sid;

    @NotNull
    private final String username;

    @NotNull
    private final String password;

    @JsonCreator
    public LoginRequest(@JsonProperty("sid") String sid,
                        @JsonProperty("username") String username,
                        @JsonProperty("password") String password) {
        this.sid = sid;
        this.username = username;
        this.password = password;
    }

    public String getSid() {
        return sid;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }
}
