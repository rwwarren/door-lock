package com.wrixton.doorlock.DAO;

import java.util.Objects;
import java.util.UUID;

public class UserID {

    private final long id;
    private final UUID uuid;

    public UserID(long id, UUID uuid) {
        this.id = id;
        this.uuid = uuid;
    }

    public long getId() {
        return id;
    }

    public UUID getUuid() {
        return uuid;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        UserID userID = (UserID) o;
        return id == userID.id &&
                Objects.equals(uuid, userID.uuid);
    }

    @Override
    public int hashCode() {
        return Objects.hash(id, uuid);
    }

    @Override
    public String toString() {
        return "UserID{" +
                "id=" + id +
                ", uuid=" + uuid +
                '}';
    }
}
