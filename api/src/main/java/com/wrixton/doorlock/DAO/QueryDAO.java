package com.wrixton.doorlock.DAO;

import com.wrixton.doorlock.mappers.DoorlockUserMapper;
import org.skife.jdbi.v2.sqlobject.Bind;
import org.skife.jdbi.v2.sqlobject.SqlQuery;
import org.skife.jdbi.v2.sqlobject.customizers.RegisterMapper;

public interface QueryDAO {

//    @SqlQuery("SELECT UserID, Name, Username, IsAdmin FROM doorlock.Users " +
//            "WHERE username = :username AND password = crypt(:password, gen_salt('sha256') AND IsActive = 1  LIMIT 1")
    @RegisterMapper(DoorlockUserMapper.class)
    @SqlQuery("SELECT my_uuid, Name, Username, IsAdmin FROM doorlock.Users " +
//    @SqlQuery("SELECT id, Name, Username, IsAdmin FROM doorlock.Users " +
//            "WHERE username = :username AND password = :password AND IsActive = true LIMIT 1")
//            "WHERE username = :username AND password = crypt(:password, gen_salt('sha256')) AND IsActive = true")
//            "WHERE username = :username AND password = crypt(:password, gen_salt('md5')) AND IsActive = true LIMIT 1")
            "WHERE username = :username AND password = digest(:password, 'sha256') AND IsActive = true LIMIT 1")
    DoorlockUserLoginCheck loginUser(@Bind("username") String username, @Bind("password") String password);

    @SqlQuery("select * from doorlock.Users where ID = :id")
    String findNameById(@Bind("id") int id);
}
