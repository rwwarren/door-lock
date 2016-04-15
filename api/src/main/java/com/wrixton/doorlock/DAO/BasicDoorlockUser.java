package com.wrixton.doorlock.DAO;

public class BasicDoorlockUser {

    private final String userID;
    private final String username;

    public BasicDoorlockUser(String userID, String username) {
        this.userID = userID;
        this.username = username;
    }

    public String getUserID() {
        return userID;
    }

    public String getUsername() {
        return username;
    }
    
}
