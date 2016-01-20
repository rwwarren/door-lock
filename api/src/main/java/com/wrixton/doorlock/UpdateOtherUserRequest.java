package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;

public class UpdateOtherUserRequest {

    @NotNull
    private final SessionRequest sid;

    public UpdateOtherUserRequest(@JsonProperty("sid") SessionRequest sid, @JsonProperty("update") OtherUserUpdate otherUserUpdate) {
        this.sid = sid;
    }

}
