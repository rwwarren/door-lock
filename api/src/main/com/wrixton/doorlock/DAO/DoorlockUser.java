package com.wrixton.doorlock.DAO;

import java.util.Objects;

public class DoorlockUser {

    private final long id;
    private final String name;
    private final String username;
    private final boolean isAdmin;

    public DoorlockUser(long id, String name, String username, boolean isAdmin) {
        this.id = id;
        this.name = name;
        this.username = username;
        this.isAdmin = isAdmin;
    }

    public long getId() {
        return id;
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
        return Objects.equals(id, that.id) &&
                Objects.equals(isAdmin, that.isAdmin) &&
                Objects.equals(name, that.name) &&
                Objects.equals(username, that.username);
    }

    @Override
    public int hashCode() {
        return Objects.hash(id, name, username, isAdmin);
    }

    @Override
    public String toString() {
        return "DoorlockUser{" +
                "id=" + id +
                ", name='" + name + '\'' +
                ", username='" + username + '\'' +
                ", isAdmin=" + isAdmin +
                '}';
    }
}
