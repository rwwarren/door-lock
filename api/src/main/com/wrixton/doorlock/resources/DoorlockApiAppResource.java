package com.wrixton.doorlock.resources;

import com.codahale.metrics.annotation.Timed;
import com.wrixton.doorlock.DAO.DoorlockUser;
import com.wrixton.doorlock.LoginRequest;
import com.wrixton.doorlock.db.Queries;
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
import java.util.Set;


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
    public DoorlockUser login(@Valid LoginRequest body) {
        try {
            Queries queries = new Queries();
            body.getSid();
            System.out.println(body.getSid());
            JedisPool pool = new JedisPool(new JedisPoolConfig(), "localhost", 6379);
            try (Jedis jedis = pool.getResource()) {
                /// ... do stuff here ... for example
//                jedis.set("foo", "bar");
                String foobar = jedis.get("foo");
                System.out.println(foobar);
//                jedis.zadd("sose", 0, "car");
//                jedis.zadd("sose", 0, "bike");
//                Set<String> sose = jedis.zrange("sose", 0, -1);
            }
            /// ... when closing your application:
            pool.destroy();
            return queries.login(body.getUsername(), body.getPassword());
        } catch (Exception e) {
            e.printStackTrace();
            System.out.println(e.getMessage());
        }
        return null;
    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/logout")
    public void logout(){

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/IsLoggedIn")
    public void isLoggedIn() {

    }

//    public void isAdmin() {
//    // not needed?
//    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetUserInfo")
    public void getUserInfo() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/GetAllUsers")
    public void getAllUsers() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/RegisterUser")
    public void registerUser() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateCurrentUser")
    public void updateCurrentUser() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/UpdateOtherUser")
    public void updateOtherUser() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ForgotPassword")
    public void forgotPassword() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/ResetPassword")
    public void resetPassword() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/LockStatus")
    public void lockStatus() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Lock")
    public void lock() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/Unlock")
    public void unlock() {

    }

    @POST
    @Timed
    @Consumes(MediaType.APPLICATION_JSON)
    @Path("/config")
    public void getConfig() {

    }

}
