package com.wrixton.doorlock;

import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;

import java.io.FileReader;

public class ConfigurationMethods {

    private static final String SALT_FILE = "src/main/resources/salt.json";

    public static String saltPassword(String username, String password) {
        JSONParser parser = new JSONParser();
        try {
            JSONObject parsed = (JSONObject) parser.parse(new FileReader(SALT_FILE));
            String firstPart = parsed.get("firstPart").toString();
            String secondPart = parsed.get("secondPart").toString();
            String thirdPart = parsed.get("thirdPart").toString();
            return String.format("doorlock%s\\%s//%s__%s+%sjava", firstPart, username, secondPart, password, thirdPart);
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

}
