package com.wrixton.doorlock.DAO;

import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;
import java.util.Objects;

public class LoginStatus {

    @NotNull
    @JsonProperty
    private final DoorlockUserLoginCheck loginCheck;

    @NotNull
    @JsonProperty
    private final Status status;

    public LoginStatus(DoorlockUserLoginCheck loginCheck, Status status) {
        this.loginCheck = loginCheck;
        this.status = status;
    }

    public DoorlockUserLoginCheck getLoginCheck() {
        return loginCheck;
    }

    public Status getStatus() {
        return status;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        LoginStatus that = (LoginStatus) o;
        return Objects.equals(status, that.status) &&
                Objects.equals(loginCheck, that.loginCheck);
    }

    @Override
    public int hashCode() {
        return Objects.hash(loginCheck, status);
    }

    @Override
    public String toString() {
        return "LoginStatus{" +
                "loginCheck=" + loginCheck +
                ", status=" + status +
                '}';
    }
}
