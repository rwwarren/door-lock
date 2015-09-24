package com.wrixton.doorlock.DAO;

import java.util.Objects;

public class DoorlockUser {

    private final String userID;
    private final String name;
    private final String username;
    private final boolean isAdmin;

    public DoorlockUser(String userID, String name, String username, boolean isAdmin) {
        this.userID = userID;
        this.name = name;
        this.username = username;
        this.isAdmin = isAdmin;
    }

    public String getUserID() {
        return userID;
    }

    public String getName() {
        return name;
    }

    public String getUsername() {
        return username;
    }

    public boolean isAdmin() {
        return isAdmin;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        DoorlockUser that = (DoorlockUser) o;
        return Objects.equals(userID, that.userID) &&
                Objects.equals(isAdmin, that.isAdmin) &&
                Objects.equals(name, that.name) &&
                Objects.equals(username, that.username);
    }

    @Override
    public int hashCode() {
        return Objects.hash(userID, name, username, isAdmin);
    }

    @Override
    public String toString() {
        return "DoorlockUser{" +
                "userID=" + userID +
                ", name='" + name + '\'' +
                ", username='" + username + '\'' +
                ", isAdmin=" + isAdmin +
                '}';
    }
}
