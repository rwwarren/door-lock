package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;

public class UpdateCurrentUserRequest {

    @NotNull
    private final SessionRequest sid;

    public UpdateCurrentUserRequest(@JsonProperty("sid") SessionRequest sid) {
        this.sid = sid;
    }
    
}
