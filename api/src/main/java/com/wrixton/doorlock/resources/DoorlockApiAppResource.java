package com.wrixton.doorlock.resources;

import com.codahale.metrics.annotation.Timed;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletRequestWrapper;
import javax.ws.rs.GET;
import javax.ws.rs.POST;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;


@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class DoorlockApiAppResource {

    @GET
    @Timed
    @Produces(MediaType.TEXT_HTML)
    public InputStream getIndex() {
        HttpClient client = new DefaultHttpClient();
        try {
            HttpGet request = new HttpGet("http://docs.doorlock.apiary.io/");
            HttpResponse response = client.execute(request);
            return response.getEntity().getContent();
        } catch (Exception e) {
            System.out.println(e.toString());
            System.out.println(e.getStackTrace().toString());
        }
        return null;
    }

    @POST
    @Timed
    @Path("/login")
    public String login(String body){
//    public Login login(String body){
        return null;
    }

}
