package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.DoorlockUser;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;

public class DoorlockUserMapper implements ResultSetMapper<DoorlockUser> {

    @Override
    public DoorlockUser map(int i, ResultSet rs, StatementContext statementContext) throws SQLException {
        String retreivedUsername = rs.getString("Username").trim();
        String userID = rs.getString("user_uuid");
        String name = rs.getString("name").trim();
        String email = rs.getString("email");
        String cardID = rs.getString("card_id");
        String authyID = rs.getString("authy_id");
        boolean isAdmin = rs.getBoolean("is_admin");
        return new DoorlockUser(userID, name, retreivedUsername, email, cardID, authyID, isAdmin);
    }

}
