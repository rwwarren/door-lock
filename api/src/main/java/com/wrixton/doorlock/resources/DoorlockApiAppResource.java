package com.wrixton.doorlock.resources;

import com.codahale.metrics.annotation.Timed;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import com.wrixton.doorlock.DAO.LoginStatus;
import com.wrixton.doorlock.DAO.Status;
import com.wrixton.doorlock.ForgotPasswordRequest;
import com.wrixton.doorlock.LoginRequest;
import com.wrixton.doorlock.RegisterUserRequest;
import com.wrixton.doorlock.ResetPasswordRequest;
import com.wrixton.doorlock.SessionRequest;
import com.wrixton.doorlock.UpdateCurrentUserRequest;
import com.wrixton.doorlock.UpdateOtherUserRequest;
import com.wrixton.doorlock.db.Queries;
import io.swagger.annotations.Api;
import io.swagger.annotations.ApiOperation;
import org.apache.commons.lang3.BooleanUtils;
import org.json.simple.parser.JSONParser;
import redis.clients.jedis.Jedis;

import javax.validation.Valid;
import javax.ws.rs.Consumes;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.client.Client;
import javax.ws.rs.client.ClientBuilder;
import javax.ws.rs.client.WebTarget;
import javax.ws.rs.core.Context;
import javax.ws.rs.core.MediaType;
import javax.ws.rs.core.Response;
import java.io.FileReader;
import java.util.List;
import java.util.Map;

//import io.swagger.annotations.Api;
//import io.swagger.annotations.ApiOperation;

@Api
@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class DoorlockApiAppResource {

    private final String CONFIG_FILE = "config.json";

    @ApiOperation("Sample endpoint")
    @GET
    @Timed
    @Produces(MediaType.TEXT_PLAIN)
//    @Produces(MediaType.TEXT_HTML)
    public Response getIndex() {
        Client myClient = ClientBuilder.newClient();
        WebTarget target = myClient.target("http://petstore.swagger.io/?url=http://localhost:8080/swagger.json");
//        WebTarget target = myClient.target("http://generator.swagger.io/");
//        WebTarget target = myClient.target("http://docs.doorlock.apiary.io/");
//        String payload = "{\"swaggerUrl\":\"http://localhost:8080/swagger.json\"}";
//        return target.request(MediaType.TEXT_HTML).post(Entity.entity(payload, MediaType.APPLICATION_JSON));
        return target.request(MediaType.TEXT_PLAIN).get();
//        return target.request(MediaType.TEXT_HTML).get();
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/login")
//    @Api()
//    @Api(value = "/pet", description = "Operations about pets")
    public LoginStatus login(@Valid LoginRequest body, @Context Jedis jedis) {
        try {
            Queries queries = new Queries();
            String sid = body.getSid();
            String token = body.getToken();
            System.out.println(sid);
            DoorlockUserLoginCheck user = queries.login(body.getUsername(), body.getPassword());
            if (user == null) {
                return new LoginStatus(null, new Status(false));
            }
            jedis.hset("loggedInUsers:" + sid, "name", user.getName());
            jedis.hset("loggedInUsers:" + sid, "username", user.getUsername());
            jedis.hset("loggedInUsers:" + sid, "UserID", "" + user.getUserID());
            jedis.hset("loggedInUsers:" + sid, "admin", "" + user.isAdmin());
            return new LoginStatus(user, new Status(true));
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return new LoginStatus(null, new Status(false));
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/logout")
    public Status logout(@Valid SessionRequest sid, @Context Jedis jedis) {
        jedis.hdel(getRedisKey(sid), "name");
        jedis.hdel(getRedisKey(sid), "username");
        jedis.hdel(getRedisKey(sid), "UserID");
        jedis.hdel(getRedisKey(sid), "admin");
        return new Status(true);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/IsLoggedIn")
    public LoginStatus isLoggedIn(@Valid SessionRequest sid, @Context Jedis jedis) {
        DoorlockUserLoginCheck user = null;
        Status status = new Status(false);
        String id = jedis.hget(getRedisKey(sid), "UserID");
        String name = jedis.hget(getRedisKey(sid), "name");
        String username = jedis.hget(getRedisKey(sid), "username");
        String admin = jedis.hget(getRedisKey(sid), "admin");
        if (id != null && name != null && username != null && admin != null) {
            user = new DoorlockUserLoginCheck(id, name, username, BooleanUtils.toBoolean(admin));
            status = new Status(true);
        }
        return new LoginStatus(user, status);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetUserInfo")
    public DoorlockUser getUserInfo(@Valid SessionRequest sid, @Context Jedis jedis) {
        try {
            String username;
            username = jedis.hget(getRedisKey(sid), "username");
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
    public Map<String, List<BasicDoorlockUser>> getAllUsers(@Valid SessionRequest sid, @Context Jedis jedis) {
        try {
            Queries queries = new Queries();
            System.out.println(sid);
            boolean isAdmin = isAdmin(sid, jedis);
            if (isAdmin) {
                return queries.getAllUsers();
            }
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return null;
    }

    private boolean isAdmin(@Valid SessionRequest sid, @Context Jedis jedis) {
        String admin;
        admin = jedis.hget(getRedisKey(sid), "admin");
        return BooleanUtils.toBoolean(admin);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/RegisterUser")
    public Status registerUser(@Valid RegisterUserRequest registerUserRequest, @Context Jedis jedis) {
        try {
            if (isAdmin(registerUserRequest.getSid(), jedis)) {
                Queries queries = new Queries();
                queries.registerUser(registerUserRequest.getUsername(), registerUserRequest.getName(),
                        registerUserRequest.getPassword(), registerUserRequest.getEmail(),
                        registerUserRequest.getAuthyID(), registerUserRequest.getCardID(), registerUserRequest.isAdmin());
                return new Status(true);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return new Status(false);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateCurrentUser")
    public Status updateCurrentUser(@Valid UpdateCurrentUserRequest updateCurrentUserRequest) {
        return new Status(false);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateOtherUser")
    public Status updateOtherUser(@Valid UpdateOtherUserRequest updateOtherUserRequest) {
        return new Status(false);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ForgotPassword")
    public Status forgotPassword(@Valid ForgotPasswordRequest forgotPasswordRequest) {
        return new Status(false);
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ResetPassword")
    public Status resetPassword(@Valid ResetPasswordRequest resetPasswordRequest) {
        return new Status(false);
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
        return "{\"Status\":\"some other status\"}";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Unlock")
    public String unlock(@Valid SessionRequest sid) {
        return "{\"Status\":\"some status\"}";
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/config")
    public Object getConfig(@Valid SessionRequest sid, @Context Jedis jedis) {
        String admin = jedis.hget(getRedisKey(sid), "admin");
        if (admin == null) {
            return null;
        }
        JSONParser jp = new JSONParser();
        try {
            return jp.parse(new FileReader(CONFIG_FILE));
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    private String getRedisKey(@Valid SessionRequest sid) {
        return "loggedInUsers:" + sid.getSid();
    }

}
