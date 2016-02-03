package com.wrixton.doorlock.DAO;

import java.util.Objects;
import java.util.UUID;

public class DoorlockUser {

    private final UUID userID;
    private final String name;
    private final String username;
    private final String email;
    private final String cardID;
    private final Long authyID;
    private final boolean isAdmin;

    public DoorlockUser(UUID userID, String name, String username, String email, Long authyID, String cardID, boolean isAdmin) {
        this.userID = userID;
        this.name = name;
        this.username = username;
        this.email = email;
        this.authyID = authyID;
        this.cardID = cardID;
        this.isAdmin = isAdmin;
    }

    public UUID getUserID() {
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

    public Long getAuthyID() {
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
                ", authyID='" + authyID + '\'' +
                ", cardID='" + cardID + '\'' +
                ", isAdmin=" + isAdmin +
                '}';
    }
}
