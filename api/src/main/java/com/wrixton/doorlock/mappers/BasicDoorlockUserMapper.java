package com.wrixton.doorlock.mappers;

import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import org.skife.jdbi.v2.StatementContext;
import org.skife.jdbi.v2.tweak.ResultSetMapper;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class BasicDoorlockUserMapper implements ResultSetMapper<BasicDoorlockUser> {
//public class BasicDoorlockUserMapper implements ResultSetMapper<List<BasicDoorlockUser>> {
    @Override
    public BasicDoorlockUser map(int index, ResultSet rs, StatementContext ctx) throws SQLException {
//    public List<BasicDoorlockUser> map(int index, ResultSet rs, StatementContext ctx) throws SQLException {
//        List<BasicDoorlockUser> results = new ArrayList<>();
//        while (rs.next()) {
            String userID = rs.getString("user_uuid");
            String username = rs.getString("username").trim();
//            results.add(new BasicDoorlockUser(userID, username));
//        }
//        return results;
        return new BasicDoorlockUser(userID, username);
    }
}
