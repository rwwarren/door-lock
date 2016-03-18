package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.UserIDInfo;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.UUID;

public class UserIDMapper implements ResultSetMapper<UserIDInfo> {

    @Override
    public UserIDInfo map(int i, ResultSet rs, StatementContext statementContext) throws SQLException {
        String uuidString = rs.getString("user_uuid");
        long id = rs.getLong("id");
        boolean isAdmin = rs.getBoolean("is_admin");
        return new UserIDInfo(id, UUID.fromString(uuidString), isAdmin);
    }
}
