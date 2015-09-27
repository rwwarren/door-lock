package com.wrixton.doorlock.db;

import com.google.common.base.Preconditions;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import org.apache.commons.lang3.StringUtils;

import java.sql.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

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

    public DoorlockUserLoginCheck login(String username, String password) {
        Preconditions.checkState(StringUtils.isNotBlank(username), "username can't be null/empty");
        Preconditions.checkState(StringUtils.isNotBlank(password), "password can't be null/empty");
        try {
            String query = "SELECT UserID, Name, Username, IsAdmin FROM Users WHERE Username= ? AND Password = PASSWORD(?) AND IsActive = 1 LIMIT 1";
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, username);
            ps.setString(2, password);
            ResultSet rs = ps.executeQuery();
            if (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String name = rs.getString("Name");
                String userID = rs.getString("UserID");
                boolean isAdmin = rs.getBoolean("IsAdmin");
                return new DoorlockUserLoginCheck(userID, name, retreivedUsername, isAdmin);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

//    public void logout() {
//
//    }
//

//    public void isAdmin() {
//    // not needed?
//    }

    public DoorlockUser getUserInfo(String username) {
        Preconditions.checkState(StringUtils.isNotBlank(username), "username can't be null/empty");
        try {
            String query = "SELECT Username, UserID, ID, Email, Name, CardID, AuthyID, IsAdmin FROM Users WHERE Username = ?";
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, username);
            ResultSet rs = ps.executeQuery();
            DoorlockUser user = null;
            while (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String userID = rs.getString("UserID");
                String name = rs.getString("Name");
                String email = rs.getString("Email");
                String cardID = rs.getString("CardID");
                String authyID = rs.getString("AuthyID");
                boolean isAdmin = rs.getBoolean("IsAdmin");
                user = new DoorlockUser(userID, name, retreivedUsername, email, cardID, authyID, isAdmin);
            }
            return user;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    public Map<String, List<BasicDoorlockUser>> getAllUsers() {
        Map<String, List<BasicDoorlockUser>> results = new HashMap<>(3);
        List<BasicDoorlockUser> allAdmins = getAllAdmins();
        List<BasicDoorlockUser> allActiveUsers = getAllActiveUsers();
        List<BasicDoorlockUser> allInactiveUsers = getAllInactiveUsers();
        results.put("Admins", allAdmins);
        results.put("ActiveUsers", allActiveUsers);
        results.put("InactiveUsers", allInactiveUsers);
        return results;
    }

    List<BasicDoorlockUser> getAllAdmins() {
        try {
            String query = "SELECT UserID, Username FROM Users WHERE IsAdmin = 1 AND IsActive = 1";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            List<BasicDoorlockUser> admins = new ArrayList<>();
            while (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String userID = rs.getString("UserID");
                admins.add(new BasicDoorlockUser(userID, retreivedUsername));
            }
            return admins;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    List<BasicDoorlockUser> getAllActiveUsers() {
        try {
            String query = "SELECT UserID, Username FROM Users WHERE IsAdmin = 0 AND IsActive = 1";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            List<BasicDoorlockUser> actives = new ArrayList<>();
            while (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String userID = rs.getString("UserID");
                actives.add(new BasicDoorlockUser(userID, retreivedUsername));
            }
            return actives;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    List<BasicDoorlockUser> getAllInactiveUsers() {
        try {
            String query = "SELECT UserID, Username FROM Users WHERE IsAdmin = 0 AND IsActive = 0";
            PreparedStatement ps = conn.prepareStatement(query);
            ResultSet rs = ps.executeQuery();
            List<BasicDoorlockUser> inactives = new ArrayList<>();
            while (rs.next()) {
                String retreivedUsername = rs.getString("Username");
                String userID = rs.getString("UserID");
                inactives.add(new BasicDoorlockUser(userID, retreivedUsername));
            }
            return inactives;
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return null;
    }

    public void registerUser() {

    }

    public void updateCurrentUser() {

    }

    public void updateOtherUser() {

    }

    public void forgotPassword() {

    }

    public void resetPassword() {

    }

//    public void lockStatus() {
//
//    }
//
//    public void lock() {
//
//    }
//
//    public void unlock() {
//
//    }

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
