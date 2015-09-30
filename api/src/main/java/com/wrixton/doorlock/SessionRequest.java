package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import javax.validation.constraints.NotNull;
import java.util.Objects;

public class SessionRequest {

    @NotNull
    private final String sid;

    public SessionRequest(@JsonProperty("sid") String sid) {
        this.sid = sid;
    }

    public String getSid() {
        return sid;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        SessionRequest sessionRequest = (SessionRequest) o;
        return Objects.equals(sid, sessionRequest.sid);
    }

    @Override
    public int hashCode() {
        return Objects.hash(sid);
    }

    @Override
    public String toString() {
        return "SessionRequest{" +
                "sid='" + sid + '\'' +
                '}';
    }
}
