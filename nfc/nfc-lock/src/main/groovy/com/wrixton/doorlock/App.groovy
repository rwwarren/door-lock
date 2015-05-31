package com.wrixton.doorlock

import javax.smartcardio.*;
import java.util.*
import java.util.logging.Logger;

/**
 * NFC Application for the doorlock
 */
public class App {
//    private static final Logger log = Logger.getLogger(App.class.name);

    //TODO move this to groovy!
    //TODO move it all to groovy

    private static ApiClient apiClient;

    public App(){
        //todo spring addition
        apiClient = new ApiClient("asdf", "asdf");
    }

    public static void main( String[] args ) throws CardException {
      System.out.println( "Hello World! Main NFC Application" );
//      log.info("testing")

        //TODO add some waiting for the card and the terminal factory?

//        for(;;){
//            System.out.println("testing");
//            call the cardInfo
//        }


    }

    private static void changeLock(){
        LOCK_STATUS lockStatus = apiClient.lockStatus();
        if(lockStatus == LOCK_STATUS.LOCKED){
            apiClient.unlock();
        } else if (lockStatus == LOCK_STATUS.UNLOCKED){
            apiClient.lock();
        } else {
            throw new IllegalStateException("Lock is is illegal state: " + lockStatus);
        }
    }

    private static boolean checkValidUser(){
        return apiClient.isValidUser();
    }

    private static void getCardInfo(){
        //      TerminalFactory factory = TerminalFactory.getDefault();
//      List<CardTerminal> terminals = factory.terminals().list();
//      System.out.println("Terminals: " + terminals);
//      // get the first terminal
//      CardTerminal terminal = terminals.get(0);
//      // establish a connection with the card
//      Card card = terminal.connect("T=0");
//      System.out.println("card: " + card);
//      // disconnect
//      card.disconnect(false);
    }

}
