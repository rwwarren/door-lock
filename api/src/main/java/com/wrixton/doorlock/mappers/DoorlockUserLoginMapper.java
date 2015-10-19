package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

public class DoorlockUserLoginMapper implements ResultSetMapper<DoorlockUserLoginCheck> {

    @Override
    public DoorlockUserLoginCheck map(int i, ResultSet rs, StatementContext statementContext) throws SQLException {
        String retreivedUsername = rs.getString("username");
        String userID = rs.getString("user_uuid");
        String name = rs.getString("name");
//        String email = rs.getString("Email");
//        String cardID = rs.getString("CardID");
//        String authyID = rs.getString("AuthyID");
        boolean isAdmin = rs.getBoolean("is_admin");
        return new DoorlockUserLoginCheck(userID, name, retreivedUsername, isAdmin);
    }

}
