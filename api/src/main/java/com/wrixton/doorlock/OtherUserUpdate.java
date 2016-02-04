package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import java.util.UUID;

public class OtherUserUpdate {

    private final UUID uuid;
    private final USER_TYPE type;

    public OtherUserUpdate(@JsonProperty("uuid") UUID uuid, @JsonProperty("type") USER_TYPE type) {
        this.uuid = uuid;
        this.type = type;
    }

    public UUID getUuid() {
        return uuid;
    }

    public USER_TYPE getType() {
        return type;
    }

    @Override
    public String toString() {
        return "OtherUserUpdate{" +
                "uuid=" + uuid +
                ", type='" + type + '\'' +
                '}';
    }
}
