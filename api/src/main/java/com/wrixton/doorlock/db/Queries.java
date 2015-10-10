package com.wrixton.doorlock.db;

import com.google.common.base.Preconditions;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import org.apache.commons.lang3.StringUtils;
import org.skife.jdbi.v2.sqlobject.Bind;
import org.skife.jdbi.v2.sqlobject.SqlQuery;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
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
            password = encryptPassword(password);
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

    public boolean registerUser(String username, String name, String password, String email, String authyID, String cardID, boolean admin) {
        password = encryptPassword(password);
        String query = "INSERT INTO Users (Name, Username, Password, Email, AuthyID, CardID, IsAdmin) VALUES (?, ?, ?, ?, ?, ? ,?)";
        try {
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, name);
            ps.setString(2, username);
            ps.setString(3, password);
            ps.setString(4, email);
            ps.setString(5, authyID);
            ps.setString(6, cardID);
            ps.setBoolean(7, admin);
            //TODO look at bottom method
            ResultSet rs = ps.executeQuery();
            //insert then look up right after
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        }
        //lookup after this
        return true;
    }

    private String encryptPassword(String password) {
        //TODO encrypt
//        HashFunction hashFunction = sha256();
        return password;
    }

    public void updateCurrentUser(String username, String name, String password, String email, String authyID, String cardID, boolean admin) {
        password = encryptPassword(password);
        String query = "UPDATE Users SET Name = ?, Email = ?, AuthyID = ?, CardID = ?, IsAdmin = ? WHERE Username = ? AND Password = PASSWORD(?)";
        try {
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, name);
            ps.setString(2, email);
            ps.setString(3, authyID);
            ps.setString(4, cardID);
            ps.setBoolean(5, admin);
            ps.setString(6, username);
            ps.setString(7, password);
            //TODO look at bottom method
            ResultSet rs = ps.executeQuery();
            //insert then look up right after
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void updateOtherUser(String usernameToChange, boolean admin, boolean active) {
        String query = "UPDATE Users SET IsAdmin = ?, IsActive = ? WHERE Username = ?";
        try {
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setBoolean(1, admin);
            ps.setBoolean(2, active);
            ps.setString(3, usernameToChange);
            //TODO look at bottom method
            ResultSet rs = ps.executeQuery();
            //insert then look up right after
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void forgotPassword(String username, String email) {
        //todo check email and username + join
        String userID = "";
        //todo sha256 generate
        String resetUrl = "";
        String query = "INSERT INTO ResetURLs (UserID, ResetURL) VALUES (?, ?)";
        try {
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, userID);
            ps.setString(2, resetUrl);
            //TODO look at bottom method
            //TODO also invalidate ResetURLs
            ResultSet rs = ps.executeQuery();
            //insert then look up right after
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void resetPassword(String username, String password) {
        password = encryptPassword(password);
        String query = "UPDATE Users SET Password = PASSWORD(?), IsActive = 1 WHERE Username = ?";
        try {
            PreparedStatement ps = conn.prepareStatement(query);
            ps.setString(1, password);
            ps.setString(2, username);
            //TODO look at bottom method
            //TODO also invalidate ResetURLs
            ResultSet rs = ps.executeQuery();
            //insert then look up right after
        } catch (SQLException e) {
            e.printStackTrace();
        }
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
