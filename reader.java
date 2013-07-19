//This is the main class that
//does stuff
import javax.smartcardio.*;

public class reader {

  public static void main(String[] args) throws CardException{

    System.out.println("Here is a test of the db");
    dbcon connection = new dbcon();
    connection.connect("read");
    connection.selectUser("ryan");
    connection.disconnect();
    NFCcard myCard = new NFCcard();
    myCard.connectCard();
  }
}

