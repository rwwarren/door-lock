package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonCreator;
import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;
import java.util.Objects;

//@JsonIgnoreProperties(ignoreUnknown = true)
public class LoginRequest {

    @NotNull
    private final String sid;

    @NotNull
    private final String username;

    @NotNull
    private final String password;

    @NotNull
    private final String token;

    @JsonCreator
    public LoginRequest(@JsonProperty("sid") String sid,
                        @JsonProperty("username") String username,
                        @JsonProperty("password") String password,
                        @JsonProperty("token") String token) {
        this.sid = sid;
        this.username = username;
        this.password = password;
        this.token = token;
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

    public String getToken() {
        return token;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        LoginRequest that = (LoginRequest) o;
        return Objects.equals(sid, that.sid) &&
                Objects.equals(username, that.username) &&
                Objects.equals(password, that.password) &&
                Objects.equals(token, that.token);
    }

    @Override
    public int hashCode() {
        return Objects.hash(sid, username, password, token);
    }

    @Override
    public String toString() {
        return "LoginRequest{" +
                "sid='" + sid + '\'' +
                ", username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", token='" + token + '\'' +
                '}';
    }
}
