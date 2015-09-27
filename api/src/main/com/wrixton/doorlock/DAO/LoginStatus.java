package com.wrixton.doorlock.DAO;

import java.util.Objects;

public class LoginStatus {

    private final DoorlockUserLoginCheck loginCheck;
    private final boolean success;

    public LoginStatus(DoorlockUserLoginCheck loginCheck, boolean success) {
        this.loginCheck = loginCheck;
        this.success = success;
    }

    public DoorlockUserLoginCheck getLoginCheck() {
        return loginCheck;
    }

    public boolean isSuccess() {
        return success;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        LoginStatus that = (LoginStatus) o;
        return Objects.equals(success, that.success) &&
                Objects.equals(loginCheck, that.loginCheck);
    }

    @Override
    public int hashCode() {
        return Objects.hash(loginCheck, success);
    }

    @Override
    public String toString() {
        return "LoginStatus{" +
                "loginCheck=" + loginCheck +
                ", status=" + success +
                '}';
    }
}
