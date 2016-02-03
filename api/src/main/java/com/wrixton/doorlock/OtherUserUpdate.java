package com.wrixton.doorlock;

import com.fasterxml.jackson.annotation.JsonProperty;

import java.util.UUID;

public class OtherUserUpdate {

    private final UUID uuid;
    private final boolean isAdmin;
    private final boolean isActive;

    public OtherUserUpdate(@JsonProperty("uuid") UUID uuid, @JsonProperty("isAdmin") boolean isAdmin, @JsonProperty("isActive") boolean isActive) {
        this.uuid = uuid;
        this.isAdmin = isAdmin;
        this.isActive = isActive;
    }

    public UUID getUuid() {
        return uuid;
    }

    public boolean isAdmin() {
        return isAdmin;
    }

    public boolean isActive() {
        return isActive;
    }

    @Override
    public String toString() {
        return "OtherUserUpdate{" +
                "uuid='" + uuid + '\'' +
                ", isAdmin=" + isAdmin +
                ", isActive=" + isActive +
                '}';
    }
}
