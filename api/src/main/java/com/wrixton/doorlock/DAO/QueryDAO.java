package com.wrixton.doorlock.DAO;

import com.wrixton.doorlock.mappers.BasicDoorlockUserMapper;
import com.wrixton.doorlock.mappers.DoorlockUserLoginMapper;
import com.wrixton.doorlock.mappers.DoorlockUserMapper;
import org.skife.jdbi.v2.sqlobject.Bind;
import org.skife.jdbi.v2.sqlobject.SqlQuery;
import org.skife.jdbi.v2.sqlobject.SqlUpdate;
import org.skife.jdbi.v2.sqlobject.customizers.RegisterMapper;

import java.util.List;
import java.util.UUID;

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
    @SqlQuery("SELECT user_uuid, username FROM doorlock.Users WHERE is_active = false")
    List<BasicDoorlockUser> getAllInactiveUsers();

    @SqlUpdate("INSERT INTO doorlock.Users (name, username, password, email, authy_id, card_id, is_admin) VALUES " +
            "(:name, :username, digest(:password, 'sha256'), :email, :authyId, :cardId, :isAdmin)")
    int registerUser(@Bind("name") String name, @Bind("username") String username, @Bind("password") String password,
                     @Bind("email") String email, @Bind("authyId") long authyId, @Bind("cardId") String cardId,
                     @Bind("isAdmin") boolean isAdmin);

    @SqlUpdate("UPDATE doorlock.Users SET name = :name, email = :email, authy_id = :authyId, card_id = :cardId," +
            " is_admin = :isAdmin WHERE username = :username AND password = digest(:password, 'sha256')")
    int updateCurrentUser(@Bind("name") String name, @Bind("email") String email, @Bind("authyId") long authyId,
                              @Bind("cardId") String cardId, @Bind("isAdmin") boolean isAdmin, @Bind("username") String username,
                              @Bind("password") String password);

    @SqlUpdate("UPDATE doorlock.Users SET is_admin = :isAdmin, is_active = :isActive WHERE user_uuid = :uuid")
    int updateOtherUser(@Bind("uuid") UUID uuid, @Bind("isAdmin") boolean isAdmin, @Bind("isActive") boolean isActive);

    @SqlUpdate("INSERT INTO doorlock.ResetURLs (user_id, reset_url) VALUES (:userID, :resetURL)")
    int forgotPassword(@Bind("userID") long userID, @Bind("resetURL") String resetURL);

    @SqlUpdate("UPDATE doorlock.Users SET Password = digest(:password, 'sha256'), is_active = true WHERE username = :username")
    int resetPassword(@Bind("username") String username, @Bind("password") String password);

    @SqlUpdate("UPDATE doorlock.Users SET is_active = :isActive WHERE username = :username")
    int changeUserStatus(@Bind("username") String username, @Bind("isActive") boolean isActive);

    @SqlQuery("SELECT user_id FROM doorlock.reseturls WHERE reset_url = :resetUrl AND is_valid = true AND expiration >= now()")
    long checkResetUrl(@Bind("resetUrl") String resetUrl);

    @SqlUpdate("UPDATE doorlock.reseturls SET is_valid = false WHERE reset_url = :resetUrl")
    int deactivateResetUrl(@Bind("resetUrl") String resetUrl);

}
