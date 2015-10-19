package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

public class BasicDoorlockUserMapper implements ResultSetMapper<BasicDoorlockUser> {

    @Override
    public BasicDoorlockUser map(int index, ResultSet rs, StatementContext ctx) throws SQLException {
        String userID = rs.getString("user_uuid");
        String username = rs.getString("username").trim();
        return new BasicDoorlockUser(userID, username);
    }
}
