package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

public class DoorlockUserMapper implements ResultSetMapper<DoorlockUserLoginCheck> {

    @Override
    public DoorlockUserLoginCheck map(int i, ResultSet rs, StatementContext statementContext) throws SQLException {
        String retreivedUsername = rs.getString("Username").trim();
        String name = rs.getString("Name").trim();
        String userID = rs.getString("my_uuid");
//        String userID = rs.getString("id");
//        String userID = rs.getString("UserID");
        boolean isAdmin = rs.getBoolean("IsAdmin");
        return new DoorlockUserLoginCheck(userID, name, retreivedUsername, isAdmin);
    }

}
