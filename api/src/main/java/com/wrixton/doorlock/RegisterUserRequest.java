package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;
import org.hibernate.validator.constraints.Email;
import org.hibernate.validator.constraints.NotEmpty;

import javax.validation.constraints.NotNull;
import java.util.Objects;

@JsonIgnoreProperties(ignoreUnknown = true)
public class RegisterUserRequest {

    @NotNull
    private final SessionRequest sessionRequest;

    @NotNull
    @NotEmpty
    private final String username;

    @NotNull
    @NotEmpty
    private final String name;

    @NotNull
    @NotEmpty
    private final String password;

    @NotNull
    @Email
    private final String email;

    private final String cardID;

    private final long authyID;

    @NotNull
    private final boolean isAdmin;

    public RegisterUserRequest(@JsonProperty("sessionRequest") SessionRequest sessionRequest,
                               @JsonProperty("name") String name,
                               @JsonProperty("username") String username,
                               @JsonProperty("password") String password,
                               @JsonProperty("email") String email,
                               @JsonProperty("cardID") String cardID,
                               @JsonProperty("authyID") long authyID,
                               @JsonProperty("isAdmin") boolean isAdmin) {
        this.sessionRequest = sessionRequest;
        this.name = name;
        this.username = username;
        this.password = password;
        this.email = email;
        this.cardID = cardID;
        this.authyID = authyID;
        this.isAdmin = isAdmin;
    }

    public SessionRequest getSessionRequest() {
        return sessionRequest;
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
                Objects.equals(sessionRequest, that.sessionRequest) &&
                Objects.equals(name, that.name) &&
                Objects.equals(username, that.username) &&
                Objects.equals(password, that.password) &&
                Objects.equals(email, that.email) &&
                Objects.equals(cardID, that.cardID) &&
                Objects.equals(authyID, that.authyID);
    }

    @Override
    public int hashCode() {
        return Objects.hash(sessionRequest, name, username, password, email, cardID, authyID, isAdmin);
    }

    @Override
    public String toString() {
        return "RegisterUserRequest{" +
                "sessionRequest=" + sessionRequest +
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
