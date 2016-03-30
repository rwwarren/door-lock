package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;
import javax.validation.constraints.NotNull;

public class UpdateOtherUserRequest {

    @NotNull
    private final SessionRequest sessionRequest;
    private final OtherUserUpdate otherUserUpdate;

    public UpdateOtherUserRequest(@JsonProperty("sessionRequest") SessionRequest sessionRequest, @JsonProperty("otherUserUpdate") OtherUserUpdate otherUserUpdate) {
        this.sessionRequest = sessionRequest;
        this.otherUserUpdate = otherUserUpdate;
    }

    public SessionRequest getSessionRequest() {
        return sessionRequest;
    }

    public OtherUserUpdate getOtherUserUpdate() {
        return otherUserUpdate;
    }

    @Override
    public String toString() {
        return "UpdateOtherUserRequest{" +
                "sessionRequest=" + sessionRequest +
                '}';
    }
}
