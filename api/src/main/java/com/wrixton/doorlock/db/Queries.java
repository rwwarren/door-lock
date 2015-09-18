package com.wrixton.doorlock.db;

import com.google.common.base.Preconditions;

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
