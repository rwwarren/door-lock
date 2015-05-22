package com.wrixton.doorlock;

import javax.smartcardio.*;
import java.util.*;
/**
 * Hello world!
 *
 */
public class App {

    public static void main( String[] args ) throws CardException {
      System.out.println( "Hello World!" );
      TerminalFactory factory = TerminalFactory.getDefault();
      List<CardTerminal> terminals = factory.terminals().list();
      System.out.println("Terminals: " + terminals);
      // get the first terminal
      CardTerminal terminal = terminals.get(0);
      // establish a connection with the card
      Card card = terminal.connect("T=0");
      System.out.println("card: " + card);
      // disconnect
      card.disconnect(false);
    }

}
