package com.wrixton.doorlock.resources;

import com.codahale.metrics.annotation.Timed;
import com.wrixton.doorlock.DAO.BasicDoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.DAO.DoorlockUserLoginCheck;
import com.wrixton.doorlock.DAO.LoginStatus;
import com.wrixton.doorlock.DAO.QueryDAO;
import com.wrixton.doorlock.DAO.Status;
import com.wrixton.doorlock.ForgotPasswordRequest;
import com.wrixton.doorlock.LoginRequest;
import com.wrixton.doorlock.OtherUserUpdate;
import com.wrixton.doorlock.RegisterUserRequest;
import com.wrixton.doorlock.ResetPasswordRequest;
import com.wrixton.doorlock.SessionRequest;
import com.wrixton.doorlock.USER_TYPE;
import com.wrixton.doorlock.UpdateCurrentUserRequest;
import com.wrixton.doorlock.UpdateOtherUserRequest;
import io.swagger.annotations.Api;
import io.swagger.annotations.ApiOperation;
import org.apache.commons.lang3.BooleanUtils;
import org.json.simple.parser.JSONParser;
import redis.clients.jedis.Jedis;

import javax.servlet.http.HttpServletRequest;
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
import java.io.FileReader;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.logging.Logger;

import static com.wrixton.doorlock.ConfigurationMethods.saltPassword;

@Api(tags = {"Api"})
@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class DoorlockApiAppResource {

    private static final Logger LOG = Logger.getLogger(DoorlockApiAppResource.class.getName());

    private final String CONFIG_FILE = "config.json";
    private final QueryDAO queriesDAO;

    public DoorlockApiAppResource(QueryDAO queriesDAO) {
        this.queriesDAO = queriesDAO;
    }

    @ApiOperation("Index")
    @GET
    @Timed
    @Produces(MediaType.TEXT_HTML)
    public Object getIndex(@Context HttpServletRequest request) throws Exception {
        Client myClient = ClientBuilder.newClient();
        WebTarget target = myClient.target(request.getRequestURL().toString() + "lib/index.htm");
        return target.request(MediaType.TEXT_HTML).get();
    }

    @ApiOperation("Log user in")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/login")
    public LoginStatus login(@Valid LoginRequest body, @Context Jedis jedis) {
        try {
            String sid = body.getSid();
            String token = body.getToken();
            System.out.println(sid);
            DoorlockUserLoginCheck user = queriesDAO.loginUser(body.getUsername(), saltPassword(body.getUsername(), body.getPassword()));
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

    @ApiOperation("Logout user in")
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

    @ApiOperation("Check if user is logged in")
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

    @ApiOperation("Get user information")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetUserInfo")
    public DoorlockUser getUserInfo(@Valid SessionRequest sid, @Context Jedis jedis) {
        try {
            String redisKey = getRedisKey(sid);
            String redisRateKey = "rate:" + redisKey;
            Long amount = jedis.incr(redisRateKey);
            int limit = 10;
            jedis.expire(redisRateKey, limit);
            if (amount <= limit) {
                System.out.println("starting to rate limit");
            }
            String username = jedis.hget(redisKey, "username");
            if (username != null) {
                return queriesDAO.getUserInfo(username);
            }
        } catch (Exception e) {
            LOG.severe(e.getLocalizedMessage());
        }
        return null;
    }

    @ApiOperation("Get all users")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetAllUsers")
    public Map<String, List<BasicDoorlockUser>> getAllUsers(@Valid SessionRequest sid, @Context Jedis jedis) {
        try {
            System.out.println(sid);
            boolean isAdmin = isAdmin(sid, jedis);
            if (isAdmin) {
                String username = jedis.hget(getRedisKey(sid), "username");
                return getAllUsers(username);
            }
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return new HashMap<>();
    }

    private boolean isAdmin(@Valid SessionRequest sid, @Context Jedis jedis) {
        String admin;
        admin = jedis.hget(getRedisKey(sid), "admin");
        return BooleanUtils.toBoolean(admin);
    }

    @ApiOperation("Register new user")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/RegisterUser")
    public Status registerUser(@Valid RegisterUserRequest registerUserRequest, @Context Jedis jedis) {
        try {
            if (isAdmin(registerUserRequest.getSessionRequest(), jedis)) {
                queriesDAO.registerUser(registerUserRequest.getName(), registerUserRequest.getUsername(),
                        saltPassword(registerUserRequest.getUsername(), registerUserRequest.getPassword()),
                        registerUserRequest.getEmail(), registerUserRequest.getAuthyID(), registerUserRequest.getCardID().trim(),
                        registerUserRequest.isAdmin());
                return new Status(true);
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return new Status(false);
    }

    @ApiOperation("Update current user")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateCurrentUser")
    public Status updateCurrentUser(@Valid UpdateCurrentUserRequest updateCurrentUserRequest) {
//        queriesDAO.updateCurrentUser(updateCurrentUserRequest.get)
        return new Status(false);
    }

    @ApiOperation("Update other user")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateOtherUser")
    public Status updateOtherUser(@Valid UpdateOtherUserRequest updateOtherUserRequest, @Context Jedis jedis) {
        SessionRequest sid = updateOtherUserRequest.getSessionRequest();
        boolean isAdmin = isAdmin(sid, jedis);
        if (!isAdmin) {
            return new Status(false);
        }
        OtherUserUpdate update = updateOtherUserRequest.getOtherUserUpdate();
        boolean userIsAdmin = false;
        boolean userIsActive = false;
        USER_TYPE type = updateOtherUserRequest.getOtherUserUpdate().getType();
        switch (type) {
            case ADMIN:
                userIsAdmin = true;
                userIsActive = true;
                break;
            case ACTIVE:
                userIsActive = true;
                break;
            case INACTIVE:
            default:
                break;
        }
        int result = queriesDAO.updateOtherUser(update.getUuid(), userIsAdmin, userIsActive);
        if (result != 1) {
            LOG.severe("Error with updateOtherUser, updated " + result + " rows with: " + updateOtherUserRequest);
            return new Status(false);
        }
        return new Status(true);
    }

    @ApiOperation("Submit forgot user forgot password")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ForgotPassword")
    public Status forgotPassword(@Valid ForgotPasswordRequest forgotPasswordRequest) {
//        queriesDAO.forgotPassword()
        return new Status(false);
    }

    @ApiOperation("Reset user password")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ResetPassword")
    public Status resetPassword(@Valid ResetPasswordRequest resetPasswordRequest) {
//        queriesDAO.forgotPassword();
//        queriesDAO.changeUserStatus();
//        queriesDAO.resetPassword()
        return new Status(false);
    }

    @ApiOperation("Check lock status")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/LockStatus")
    public String lockStatus(@Valid SessionRequest sid, @Context Jedis jedis) {
        //        String lockStatus = jedis.get("lockStatus");
        return "{\"Status\":\"open\",\"isLocked\":\"1\"}";
    }

    @ApiOperation("Lock the lock")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Lock")
    public String lock(@Valid SessionRequest sid, @Context Jedis jedis) {
//        jedis.set("lockStatus", "isLocked");
        return "{\"Status\":\"some other status\"}";
    }

    @ApiOperation("Unlock the lock")
    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Unlock")
    public String unlock(@Valid SessionRequest sid, @Context Jedis jedis) {
//        jedis.set("lockStatus", "isLocked");
        return "{\"Status\":\"some status\"}";
    }

    @ApiOperation("Get config information")
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

    private Map<String, List<BasicDoorlockUser>> getAllUsers(String currentUsername) {
        Map<String, List<BasicDoorlockUser>> results = new HashMap<>(3);
        List<BasicDoorlockUser> allAdmins = queriesDAO.getAllAdmins(currentUsername);
        List<BasicDoorlockUser> allActiveUsers = queriesDAO.getAllActiveUsers();
        List<BasicDoorlockUser> allInactiveUsers = queriesDAO.getAllInactiveUsers();
        results.put("Admins", allAdmins);
        results.put("ActiveUsers", allActiveUsers);
        results.put("InactiveUsers", allInactiveUsers);
        return results;
    }

}
