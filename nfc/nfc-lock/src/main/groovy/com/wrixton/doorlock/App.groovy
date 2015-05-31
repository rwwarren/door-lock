package com.wrixton.doorlock

import org.nfctools.scio.TerminalMode

import javax.smartcardio.*;
//import com.acs.smartcard.Reader;
import java.util.*
import java.util.logging.Logger;
import org.nfctools.*;


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
        attemptNFCTool()
//        for(int i = 0; i < 2; i++){
////        for(;;){
//            System.out.println("testing")
//            try{
//                getCardInfo()
//            } catch(CardException e){
//
//            }
//            sleep(2000)
////            call the cardInfo
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
      TerminalFactory factory = TerminalFactory.getDefault();
      List<CardTerminal> terminals = factory.terminals().list();

        System.out.println("Terminals: " + terminals);
      // get the first terminal
      CardTerminal terminal = terminals.get(0);
      terminal.waitForCardPresent(0);
      // establish a connection with the card
      Card card = terminal.connect("DIRECT");
        card.beginExclusive();
//        putReaderInInitiatorMode();
//      Card card = terminal.connect("T=0");
      System.out.println("card: " + card);
      println "card ATR: " + card.ATR
      println "card dump: " + card.dump()
//      println "card : " + card
        // disconnect
      card.disconnect(false);
    }

    def attemtNFCTools(){
//        NfcAdapter nfcAdapter = new NfcAdapter(TerminalUtils.getAvailableTerminal(), TerminalMode.INITIATOR, this);
        MfNdefReader ndefReader = new MfNdefReader(readerWriter, decoder);

    }

}
