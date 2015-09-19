package com.wrixton.doorlock.db;

import com.google.common.base.Preconditions;
import com.wrixton.doorlock.DAO.DoorlockUser;

import java.sql.*;

public class Queries {

    private Connection conn = null;

    public Queries() throws Exception {
        try {
//            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/doorlock?user=read&password=PASSWORD");
//            conn = DriverManager.getConnection("jdbc:mysql://localhost/doorlock?user=write&password=PASSWORD");
            String user = "read";
            String pass = "PASSWORD";
            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/doorlock",
                    user, pass);
        } catch (Exception e) {
            System.out.println(e);
            throw e;
        }
    }

    public DoorlockUser login(String username, String password) {
        try {
            String query = "SELECT ID, Name, Username, IsAdmin FROM Users WHERE Username= ? AND Password = PASSWORD(?) AND IsActive = 1 LIMIT 1";
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, username);
            ps.setString(2, password);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String name = rs.getString("Name");
                long id = rs.getLong("ID");
                boolean isAdmin = rs.getBoolean("IsAdmin");
                return new DoorlockUser(id, name, retreivedUsername, isAdmin);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    public String getName() {
        try {
            ResultSet rs = conn.createStatement().executeQuery("SELECT * FROM Users");
            if (rs.next()) {
                String username = rs.getString("Username");
                System.out.println(username);

                return username;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return "";
    }

    public String modfityOrUpdate() {
        String transaction = "testing";
        Preconditions.checkNotNull(transaction, "transaction cannot be null!");

        Boolean oldAutoCommit = null;
//        try (Connection connection = dataSourceSupplier.get().getConnection()) {
//            try {
//                oldAutoCommit = connection.getAutoCommit();
//                connection.setAutoCommit(false);
//
//                transaction.execute(connection);
//
//                connection.commit();
//            } catch (Exception e) {
//                try {
//                    connection.rollback();
//                } catch (Throwable t) {
////                    LOG.error("Exception trying to rollback transaction", t);
//                }
//                throw e;
//            } finally {
//                if (oldAutoCommit != null) {
//                    try {
//                        connection.setAutoCommit(oldAutoCommit);
//                    } catch (SQLException e) {
////                        LOG.error("Could not reset auto commit", e);
//                    }
//                }
//            }
//        }
        return "testing";
    }
}
