package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;

public class UpdateOtherUserRequest {

    @NotNull
    private final SessionRequest sid;
    private final OtherUserUpdate otherUserUpdate;

    public UpdateOtherUserRequest(@JsonProperty("sid") SessionRequest sid, @JsonProperty("otherUserUpdate") OtherUserUpdate otherUserUpdate) {
        this.sid = sid;
        this.otherUserUpdate = otherUserUpdate;
    }

    public SessionRequest getSid() {
        return sid;
    }

    public OtherUserUpdate getOtherUserUpdate() {
        return otherUserUpdate;
    }

    @Override
    public String toString() {
        return "UpdateOtherUserRequest{" +
                "sid=" + sid +
                '}';
    }
}
