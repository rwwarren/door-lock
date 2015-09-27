package com.wrixton.doorlock.resources;

import com.codahale.metrics.annotation.Timed;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import com.wrixton.doorlock.DAO.LoginStatus;
import com.wrixton.doorlock.LoginRequest;
import com.wrixton.doorlock.SessionRequest;
import com.wrixton.doorlock.db.Queries;
import org.apache.commons.lang3.BooleanUtils;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;
import redis.clients.jedis.Jedis;
import redis.clients.jedis.JedisPool;
import redis.clients.jedis.JedisPoolConfig;

import javax.validation.Valid;
import javax.ws.rs.client.Client;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Produces;
import javax.ws.rs.Consumes;
import javax.ws.rs.Path;
import java.util.List;
import java.util.Map;


@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class DoorlockApiAppResource {

    @GET
    @Timed
    @Produces(MediaType.TEXT_HTML)
    public Response getIndex() {
        Client myClient = ClientBuilder.newClient();
        WebTarget target = myClient.target("http://docs.doorlock.apiary.io/");
        return target.request(MediaType.TEXT_HTML).get();
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/login")
    public LoginStatus login(@Valid LoginRequest body) {
        try {
            Queries queries = new Queries();
            String sid = body.getSid();
            String token = body.getToken();
            System.out.println(sid);
            DoorlockUserLoginCheck user = queries.login(body.getUsername(), body.getPassword());
            if (user == null) {
                return new LoginStatus(null, false);
            }
            JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
            try (Jedis jedis = pool.getResource()) {
                /// ... do stuff here ... for example
                jedis.hset("loggedInUsers:" + sid, "name", user.getName());
                jedis.hset("loggedInUsers:" + sid, "username", user.getUsername());
                jedis.hset("loggedInUsers:" + sid, "UserID", "" + user.getUserID());
                jedis.hset("loggedInUsers:" + sid, "admin", "" + user.isAdmin());
            }
            /// ... when closing your application:
            pool.destroy();
            return new LoginStatus(user, true);
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return new LoginStatus(null, false);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/logout")
    public String logout(@Valid SessionRequest sid) {
        JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
        try (Jedis jedis = pool.getResource()) {
            /// ... do stuff here ... for example
            jedis.hdel("loggedInUsers:" + sid.getSid(), "name");
            jedis.hdel("loggedInUsers:" + sid.getSid(), "username");
            jedis.hdel("loggedInUsers:" + sid.getSid(), "UserID");
            jedis.hdel("loggedInUsers:" + sid.getSid(), "admin");
        }
        /// ... when closing your application:
        pool.destroy();
        return "{\"Success\":\"1\"}";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/IsLoggedIn")
    public LoginStatus isLoggedIn(@Valid SessionRequest sid) {
        JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
        DoorlockUserLoginCheck user = null;
        boolean status = false;
        try (Jedis jedis = pool.getResource()) {
            /// ... do stuff here ... for example
            String id = jedis.hget("loggedInUsers:" + sid.getSid(), "UserID");
            String name = jedis.hget("loggedInUsers:" + sid.getSid(), "name");
            String username = jedis.hget("loggedInUsers:" + sid.getSid(), "username");
            String admin = jedis.hget("loggedInUsers:" + sid.getSid(), "admin");
            if (id != null && name != null && username != null && admin != null) {
                //make success = 1
                user = new DoorlockUserLoginCheck(id, name, username, BooleanUtils.toBoolean(admin));
                status = true;
            }
        }
        /// ... when closing your application:
        pool.destroy();
        return new LoginStatus(user, status);
    }

//    public void isAdmin() {
//    // not needed?
//    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetUserInfo")
    public DoorlockUser getUserInfo(@Valid SessionRequest sid) {
        try {
            JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
            String username = null;
            try (Jedis jedis = pool.getResource()) {
                /// ... do stuff here ... for example
                username = jedis.hget("loggedInUsers:" + sid.getSid(), "username");
            }
            /// ... when closing your application:
            pool.destroy();
            if (username != null) {
                Queries queries = new Queries();
                return queries.getUserInfo(username);
            }
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return null;
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetAllUsers")
    public Map<String, List<BasicDoorlockUser>> getAllUsers(@Valid SessionRequest sid) {
        try {
            Map<String, List<BasicDoorlockUser>> allUsers = null;
            Queries queries = new Queries();
            System.out.println(sid);
            JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
            String admin;
            try (Jedis jedis = pool.getResource()) {
                /// ... do stuff here ... for example
                admin = jedis.hget("loggedInUsers:" + sid.getSid(), "admin");
            }
            /// ... when closing your application:
            pool.destroy();
            boolean isAdmin = BooleanUtils.toBoolean(admin);
            if (isAdmin) {
                allUsers = queries.getAllUsers();
            }
            return allUsers;
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return null;
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/RegisterUser")
    public void registerUser(@Valid SessionRequest sid) {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateCurrentUser")
    public void updateCurrentUser(@Valid SessionRequest sid) {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateOtherUser")
    public void updateOtherUser(@Valid SessionRequest sid) {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ForgotPassword")
    public void forgotPassword(@Valid SessionRequest sid) {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ResetPassword")
    public void resetPassword(@Valid SessionRequest sid) {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/LockStatus")
    public String lockStatus(@Valid SessionRequest sid) {
        return "{\"Status\":\"open\",\"isLocked\":\"1\"}";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Lock")
    public String lock(@Valid SessionRequest sid) {
        return "some other status";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Unlock")
    public String unlock(@Valid SessionRequest sid) {
        return "some status";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/config")
    public Object getConfig(@Valid SessionRequest sid) {
//    public JSONObject getConfig() {
        JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
        try (Jedis jedis = pool.getResource()) {
            /// ... do stuff here ... for example
            String admin = jedis.hget("loggedInUsers:" + sid.getSid(), "admin");
            if (admin == null) {
                return null;
            }
        }
        /// ... when closing your application:
        pool.destroy();
        JSONParser jp = new JSONParser();
        try {
//        InputStream is = JsonParsing.class.getResourceAsStream( "sample-json.txt");
            return jp.parse("{\"config\":\"testing\"}");
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return null;
    }

}