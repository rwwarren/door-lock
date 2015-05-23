package com.wrixton.doorlock;

//This is the main class that
//does stuff
import javax.smartcardio.*;
import java.util.*;

public class reader {

  public static void main(String[] args) throws CardException{

    //TODO make it so that the cards are not all equal
    //since kelly and mine are
//    System.out.println("Here is a test of the db");
//    dbcon connection = new dbcon();
//    connection.connect("read");
//    //connection.connect("write");
//    //connection.selectUser("ryan");
//    //connection.disconnect();
//    NFCcard myCard = new NFCcard();
//    String test = "";
//    String test2 = "";
//    for (int i = 0; i < 1; i++){//;;){
//      myCard.connectCard();
//      connection.selectUser(myCard.cardInfo());
//      if (i == 0){ 
//        test = myCard.cardInfo();
//      }
//      test2 = myCard.cardInfo();
//      if (test.equals(test2)){
//        System.out.println("They are equal!!!!");
//      }
//      System.out.println(myCard.cardInfo()); 
//      System.out.println(i);
//      //TODO this just added the user
//      //connection.addUser("Ryan", myCard.cardInfo());
//      connection.selectUser(myCard.cardInfo());
//      myCard.disconnectCard();
//    }
//    connection.disconnect();
    // show the list of available terminals
    TerminalFactory factory = TerminalFactory.getDefault();
    List<CardTerminal> terminals = factory.terminals().list();
    System.out.println("Terminals: " + terminals);
    // get the first terminal
    CardTerminal terminal = terminals.get(0);
    // establish a connection with the card
    Card card = terminal.connect("T=0");
    System.out.println("card: " + card);
//    CardChannel channel = card.getBasicChannel();
//    byte[] c1 = new byte['a'];
//    ResponseAPDU r = channel.transmit(new CommandAPDU(c1));
////    System.out.println("response: " + toString(r.getBytes()));
//    System.out.println("response: " + r.getBytes());
//    // disconnect
    card.disconnect(false);
  }
}

