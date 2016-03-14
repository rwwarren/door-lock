package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.UserID;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.UUID;
import java.util.logging.Logger;

public class UserIDMapper implements ResultSetMapper<UserID> {

    private static final Logger LOG = Logger.getLogger(UserIDMapper.class.getName());

    @Override
    public UserID map(int i, ResultSet rs, StatementContext statementContext) throws SQLException {
        String uuidString = rs.getString("user_uuid");
        long id = rs.getLong("id");
        return new UserID(id, UUID.fromString(uuidString));
    }
}
