//import com.mongodb.*;
import java.sql.*;
import com.mysql.jdbc.Driver;

//This will connect to the db
//db will be mongo db
//and will edit and such

//TODO add some security to this to make sure the user can "Edit" but 
//can try login otherwise
public class dbcon {

  private Connection conn;

  public dbcon (){
    try {
      Class.forName("com.mysql.jdbc.Driver").newInstance();
    } catch (Exception ex) {
      System.out.println("jdbc Driver broken");
    }
  }

    //TODO: create user
    //TODO: update user
    //TODO: delete user: update user, but with change the flag from active to not
    //TODO: read / select user

    public void connect(String type){
      try {
        String user = "";
        String pass = "";
        if (type.equalsIgnoreCase("read")){
          mysqlLogin login = new mysqlLogin('r');
          user = login.getUser();
          pass = login.getPass();
        } else if (type.equalsIgnoreCase("write")){
          mysqlLogin login = new mysqlLogin('w');
          user = login.getUser();
          pass = login.getPass();
        } else {
          throw new IllegalArgumentException("The connection did not have a valid type passed in " + type);
        }
        conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/reader",
              user, pass);
      } catch (SQLException ex) {
        System.out.println("SQLException: " + ex.getMessage());
      }
    }

    public void disconnect(){
      try {
        conn.close();
      } catch (SQLException ex) {
        System.out.println("SQLException: " + ex.getMessage());
      }
    }

    //TODO maybe remove the connect and disconnect
    //functions and call it from in here
    public void addUser(String user, String id){
      try {
        Statement selection = conn.createStatement();
        /*ResultSet *///int result = selection.executeUpdate("INSERT INTO users " + /*(id, name, id_number, user_is_current, date_created)*/ "VALUES (DEFAULT, \"" + user + "\", PASSWORD(\"" + id + "\"), 1, DEFAULT);");
        int result = selection.executeUpdate("INSERT INTO users VALUES (DEFAULT, \"" + user + "\", PASSWORD(\"" + id + "\"), 1, DEFAULT);");
      } catch (SQLException ex){
        System.out.println("SQLException: " + ex.getMessage());
      }

    }

    //TODO make sure that the user is current AKA 1
    public void selectUser(String user){
      try {
        Statement selection = conn.createStatement();
        //ResultSet result = selection.executeQuery("SELECT PASSWORD(\"" + user + "\") FROM users");
        /*
        System.out.println("Here are the columns in the table");
        ResultSet result = selection.executeQuery("show columns from users");
        while(result.next()){
          System.out.println(result.getString(1));
        }*/
        System.out.println("Here are the users");
        ResultSet result = selection.executeQuery("select * from users where name = \"" + user + "\" and user_is_current = \"1\";");
        while(result.next()){
          System.out.println(result.getString(0));
        }
      } catch (SQLException ex){
          System.out.println("SQLException: " + ex.getMessage());
      }

    }

    //TODO test on the DB called "test"
    public void removeUser(String user){
        //Remove user
        //ResultSet result = selection.executeQuery("UPDATE users SET user_is_current = 0 WHERE name = \" " + user + "\";");

    }

}
