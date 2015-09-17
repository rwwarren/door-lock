package com.wrixton.doorlock.db;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;

public class Queries {
    private Connection conn = null;

    public Queries() throws Exception {
        try {
//            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/doorlock");
//            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/doorlock?user=read&password=PASSWORD");
//            conn = DriverManager.getConnection("jdbc:mysql://localhost/doorlock?user=write&password=PASSWORD");

            String user = "read";
            String pass = "PASSWORD";
            conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/doorlock",
                    user, pass);
            // Do something with the Connection
//        } catch (SQLException ex) {
            // handle any errors
//            System.out.println("SQLException: " + ex.getMessage());
//            System.out.println("SQLState: " + ex.getSQLState());
//            System.out.println("VendorError: " + ex.getErrorCode());
        } catch (Exception e) {
            System.out.println(e);
            throw e;
        }
    }

    public String getName() {
        try {
            ResultSet rs = conn.createStatement().executeQuery("SELECT * FROM Users");
            if(rs.next()){
                String username = rs.getString("Username");
                System.out.println(username);

                return username;
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return "";
    }
}
