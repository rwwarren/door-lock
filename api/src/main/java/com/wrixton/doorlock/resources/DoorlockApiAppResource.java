package com.wrixton.doorlock.resources;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;

@Path("/")
@Produces(MediaType.APPLICATION_JSON)
public class DoorlockApiAppResource {

    @GET
    public String testing(){
        return "this is a sick test!";
    }
}
