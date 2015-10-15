package com.wrixton.doorlock.DAO;

import org.skife.jdbi.v2.sqlobject.Bind;
import org.skife.jdbi.v2.sqlobject.SqlQuery;

public interface QueryDAO {

    @SqlQuery("select * from doorlock.Users where ID = :id")
    String findNameById(@Bind("id") int id);
}
