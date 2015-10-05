package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonIgnoreProperties;
import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;

@JsonIgnoreProperties(ignoreUnknown = true)
public class ForgotPasswordRequest {

    @NotNull
    private final SessionRequest sessionRequest;

//    private final User user;

    public ForgotPasswordRequest(@JsonProperty("sessionRequest") SessionRequest sessionRequest) {
        this.sessionRequest = sessionRequest;
    }


}
