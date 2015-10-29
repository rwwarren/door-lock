package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import org.hibernate.validator.constraints.Email;

import javax.validation.constraints.NotNull;
import java.util.Objects;

@JsonIgnoreProperties(ignoreUnknown = true)
public class RegisterUserRequest {

    @NotNull
    private final SessionRequest sid;

    @NotNull
    private final String userID;

    @NotNull
    private final String name;

    @NotNull
    private final String username;

    @NotNull
    private final String password;

    @NotNull
    @Email
    private final String email;

    @NotNull
    private final String cardID;

    @NotNull
    private final long authyID;

    @NotNull
    private final boolean isAdmin;

    public RegisterUserRequest(@JsonProperty("sid") SessionRequest sid,
                               @JsonProperty("userID") String userID,
                               @JsonProperty("name") String name,
                               @JsonProperty("username") String username,
                               @JsonProperty("password") String password,
                               @JsonProperty("email") String email,
                               @JsonProperty("cardID") String cardID,
                               @JsonProperty("authyID") long authyID,
                               @JsonProperty("isAdmin") boolean isAdmin) {
        this.sid = sid;
        this.userID = userID;
        this.name = name;
        this.username = username;
        this.password = password;
        this.email = email;
        this.cardID = cardID;
        this.authyID = authyID;
        this.isAdmin = isAdmin;
    }

    public SessionRequest getSid() {
        return sid;
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

    public String getPassword() {
        return password;
    }

    public String getEmail() {
        return email;
    }

    public String getCardID() {
        return cardID;
    }

    public long getAuthyID() {
        return authyID;
    }

    public boolean isAdmin() {
        return isAdmin;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        RegisterUserRequest that = (RegisterUserRequest) o;
        return Objects.equals(isAdmin, that.isAdmin) &&
                Objects.equals(sid, that.sid) &&
                Objects.equals(userID, that.userID) &&
                Objects.equals(name, that.name) &&
                Objects.equals(username, that.username) &&
                Objects.equals(password, that.password) &&
                Objects.equals(email, that.email) &&
                Objects.equals(cardID, that.cardID) &&
                Objects.equals(authyID, that.authyID);
    }

    @Override
    public int hashCode() {
        return Objects.hash(sid, userID, name, username, password, email, cardID, authyID, isAdmin);
    }

    @Override
    public String toString() {
        return "RegisterUserRequest{" +
                "sid=" + sid +
                ", userID='" + userID + '\'' +
                ", name='" + name + '\'' +
                ", username='" + username + '\'' +
                ", password='" + password + '\'' +
                ", email='" + email + '\'' +
                ", cardID='" + cardID + '\'' +
                ", authyID='" + authyID + '\'' +
                ", isAdmin=" + isAdmin +
                '}';
    }

}
