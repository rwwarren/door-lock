package com.wrixton.doorlock.DAO;

import java.util.Objects;

public class DoorlockUser {

    private final String userID;
    private final String name;
    private final String username;
    private final String email;
    private final String cardID;
    private final String authyID;
    private final boolean isAdmin;

    public DoorlockUser(String userID, String name, String username, String email, String cardID, String authyID, boolean isAdmin) {
        this.userID = userID;
        this.name = name;
        this.username = username;
        this.email = email;
        this.cardID = cardID;
        this.authyID = authyID;
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

    public String getEmail() {
        return email;
    }

    public String getCardID() {
        return cardID;
    }

    public String getAuthyID() {
        return authyID;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        DoorlockUser that = (DoorlockUser) o;
        return Objects.equals(isAdmin, that.isAdmin) &&
                Objects.equals(userID, that.userID) &&
                Objects.equals(name, that.name) &&
                Objects.equals(username, that.username) &&
                Objects.equals(email, that.email) &&
                Objects.equals(cardID, that.cardID) &&
                Objects.equals(authyID, that.authyID);
    }

    @Override
    public int hashCode() {
        return Objects.hash(userID, name, username, email, cardID, authyID, isAdmin);
    }

    @Override
    public String toString() {
        return "DoorlockUser{" +
                "userID='" + userID + '\'' +
                ", name='" + name + '\'' +
                ", username='" + username + '\'' +
                ", email='" + email + '\'' +
                ", cardID='" + cardID + '\'' +
                ", authyID='" + authyID + '\'' +
                ", isAdmin=" + isAdmin +
                '}';
    }
}
