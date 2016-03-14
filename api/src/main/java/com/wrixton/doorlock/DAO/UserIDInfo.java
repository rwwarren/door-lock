package com.wrixton.doorlock.DAO;

import java.util.Objects;
import java.util.UUID;

public class UserIDInfo {

    private final long id;
    private final UUID uuid;
    private final boolean isAdmin;

    public UserIDInfo(long id, UUID uuid, boolean isAdmin) {
        this.id = id;
        this.uuid = uuid;
        this.isAdmin = isAdmin;
    }

    public long getId() {
        return id;
    }

    public UUID getUuid() {
        return uuid;
    }

    public boolean isAdmin() {
        return isAdmin;
    }

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        UserIDInfo that = (UserIDInfo) o;
        return id == that.id &&
                isAdmin == that.isAdmin &&
                Objects.equals(uuid, that.uuid);
    }

    @Override
    public int hashCode() {
        return Objects.hash(id, uuid, isAdmin);
    }

    @Override
    public String toString() {
        return "UserIDInfo{" +
                "id=" + id +
                ", uuid=" + uuid +
                ", isAdmin=" + isAdmin +
                '}';
    }
}
