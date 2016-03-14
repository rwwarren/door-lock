package com.wrixton.doorlock;

import java.util.Objects;

public class ResetPasswordRequest {

    private final String username;
    private final String password;
    private final String resetUrl;

    public ResetPasswordRequest(String username, String password, String resetUrl) {
        this.username = username;
        this.password = password;
        this.resetUrl = resetUrl;
    }

    public String getUsername() {
        return username;
    }

    public String getPassword() {
        return password;
    }

    public String getResetUrl() {
        return resetUrl;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        ResetPasswordRequest that = (ResetPasswordRequest) o;
        return Objects.equals(username, that.username) &&
                Objects.equals(password, that.password) &&
                Objects.equals(resetUrl, that.resetUrl);
    }

    @Override
    public int hashCode() {
        return Objects.hash(username, password, resetUrl);
    }

    @Override
    public String toString() {
        return "ResetPasswordRequest{" +
                "username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", resetUrl='" + resetUrl + '\'' +
                '}';
    }
}
