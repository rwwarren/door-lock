package com.wrixton.doorlock.DAO;

import com.wrixton.doorlock.mappers.BasicDoorlockUserMapper;
import com.wrixton.doorlock.mappers.DoorlockUserLoginMapper;
import com.wrixton.doorlock.mappers.DoorlockUserMapper;
import org.skife.jdbi.v2.sqlobject.Bind;
import org.skife.jdbi.v2.sqlobject.SqlQuery;
import org.skife.jdbi.v2.sqlobject.SqlUpdate;
import org.skife.jdbi.v2.sqlobject.customizers.RegisterMapper;

import java.util.List;

public interface QueryDAO {

    @RegisterMapper(DoorlockUserLoginMapper.class)
    @SqlQuery("SELECT user_uuid, name, username, is_admin FROM doorlock.Users " +
            "WHERE username = :username AND password = digest(:password, 'sha256') AND is_active = true LIMIT 1")
    DoorlockUserLoginCheck loginUser(@Bind("username") String username, @Bind("password") String password);

    @RegisterMapper(DoorlockUserMapper.class)
    @SqlQuery("SELECT username, user_uuid, email, name, card_id, authy_id, is_admin FROM doorlock.Users WHERE username = :username")
    DoorlockUser getUserInfo(@Bind("username") String username);

    @RegisterMapper(BasicDoorlockUserMapper.class)
    @SqlQuery("SELECT user_uuid, username FROM doorlock.Users WHERE is_admin = true AND is_active = true")
    List<BasicDoorlockUser> getAllAdmins();

    @RegisterMapper(BasicDoorlockUserMapper.class)
    @SqlQuery("SELECT user_uuid, username FROM doorlock.Users WHERE is_admin = false AND is_active = true")
    List<BasicDoorlockUser> getAllActiveUsers();

    @RegisterMapper(BasicDoorlockUserMapper.class)
    @SqlQuery("SELECT user_uuid, username FROM doorlock.Users WHERE is_admin = false AND is_active = false")
    List<BasicDoorlockUser> getAllInactiveUsers();

//    @RegisterMapper(DoorlockUserMapper.class)
    @SqlUpdate("INSERT INTO doorlock.Users (name, username, password, email, authy_id, card_id, is_admin) VALUES " +
//    @SqlQuery("INSERT INTO doorlock.Users (name, username, password, email, authy_id, card_id, is_admin) VALUES " +
            "(:name, :username, digest(:password, 'sha256'), :email, :authyId, :cardId, :isAdmin)" +
//            "RETURNING user_uuid" +
            "")
//    void registerUser(@Bind("name") String name, @Bind("username") String username, @Bind("password") String password,
    int registerUser(@Bind("name") String name, @Bind("username") String username, @Bind("password") String password,
                         @Bind("email") String email, @Bind("authyId") long authyId, @Bind("cardId") String cardId,
                         @Bind("isAdmin") boolean isAdmin);

    @SqlQuery("UPDATE doorlock.Users SET name = :name, email = :email, authy_id = :authyId, card_id = :cardId," +
            " is_admin = :isAdmin WHERE username = :username AND password = digest(:password, 'sha256')")
    boolean updateCurrentUser(@Bind("name") String name, @Bind("username") String username, @Bind("password") String password,
                              @Bind("email") String email, @Bind("authyId") long authyId, @Bind("cardId") String cardId,
                              @Bind("isAdmin") boolean isAdmin);

    @SqlQuery("UPDATE doorlock.Users SET is_admin = :isAdmin, is_active = :isActive WHERE username = :username")
    boolean updateOtherUser(@Bind("isAdmin") boolean isAdmin, @Bind("isActive") boolean isActive, @Bind("username") String username);

    @SqlQuery("INSERT INTO doorlock.ResetURLs (user_id, reset_url) VALUES (:userID, :resetURL)")
    boolean forgotPassword(@Bind("userID") String userID, @Bind("resetURL") String resetURL);

    @SqlQuery("UPDATE doorlock.Users SET Password = digest(:password, 'sha256'), is_active = 1 WHERE username = ? = :username")
    boolean resetPassword(@Bind("username") String username, @Bind("password") String password);


}
