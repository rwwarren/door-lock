package com.wrixton.doorlock

import groovy.util.logging.Log4j
import groovy.util.logging.*


//import org.nfctools.scio.TerminalMode

import javax.smartcardio.*;
//import com.acs.smartcard.Reader;
import java.util.*
import java.util.logging.LogManager
import java.util.logging.Logger;
//import org.nfctools.*;
//import groovy.util.logging.Slf4j
//import org.apache.log4j.PropertyConfigurator;
//import org.slf4j.*;



/**
 * NFC Application for the doorlock
 */
//@Slf4j
@Log
//@Log4j
public class App {
//    private static final Logger log = Logger.getLogger(App.class);
//    private static final Logger log = LogManager.getLogger(App.class);
//    private static final Logger log = LogManager.getLogger(App.class.name);
//    private static final Logger log = Logger.getLogger(App.class.name);
//    static Logger LOGGER = LoggerFactory.getLogger(Hello.class);


    //TODO move this to groovy!
    //TODO move it all to groovy
    private byte[] atr = null;
    private String protocol = null;
    private byte[] historical = null;

    private static ApiClient apiClient;

    public App(){
        //todo spring addition
        apiClient = new ApiClient("asdf", "asdf");
    }

    public static void main( String[] args ) throws CardException {
//        PropertyConfigurator.configure("log4j.properties");
        System.setProperty("java.util.logging.SimpleFormatter.format", '[%4$s]: %1$tF %1$tT: %5$s%n')
//                '[%1$tF %1$tT]:%4$s:(%2$s): %5$s%n')
//                '[%1$tF %1$tT]:%4$s:(%1$Tp): %5$s%n')
//                '[%1$tF %1$tT]:%4$s:(%2$s): %5$s%n')
        System.out.println( "Hello World! Main NFC Application" );
//      log.info( "Hello World! Main NFC Application" );
        log.info 'Simple sample to show log field is injected.'

//      log.info(App.class.name);
//      log.info("testing")

        //TODO add some waiting for the card and the terminal factory?
        for(;;){
            def uid = attemptNFCTool()
            if(uid != null && uid.length() > 0){
                changeLock(uid)
            }
            sleep(1000)
        }
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

    private static void changeLock(uid){
        LOCK_STATUS lockStatus = apiClient.lockStatus();
        if(lockStatus == LOCK_STATUS.LOCKED){
            apiClient.unlock(uid);
        } else if (lockStatus == LOCK_STATUS.UNLOCKED){
            apiClient.lock(uid);
        } else {
            throw new IllegalStateException("Lock is is illegal state: " + lockStatus);
        }
    }

    private static boolean checkValidUser(){
        return apiClient.isValidUser();
    }

//    private static void getCardInfo(){
//      TerminalFactory factory = TerminalFactory.getDefault();
//      List<CardTerminal> terminals = factory.terminals().list();
//
//        System.out.println("Terminals: " + terminals);
//      // get the first terminal
//      CardTerminal terminal = terminals.get(0);
//      terminal.waitForCardPresent(0);
//      // establish a connection with the card
//      Card card = terminal.connect("DIRECT");
//        card.beginExclusive();
////        putReaderInInitiatorMode();
////      Card card = terminal.connect("T=0");
//      System.out.println("card: " + card);
//      println "card ATR: " + card.ATR
//      println "card dump: " + card.dump()
////      println "card : " + card
//        // disconnect
//      card.disconnect(false);
//    }

    def attemtNFCTools(){
//        NfcAdapter nfcAdapter = new NfcAdapter(TerminalUtils.getAvailableTerminal(), TerminalMode.INITIATOR, this);
//        MfNdefReader ndefReader = new MfNdefReader(readerWriter, decoder);

    }

    def static attemptNFCTool() {
        try {
            //get UID cmd apdu
            def mybytes = new byte[5]
            mybytes[0] = (byte) 0xFF
            mybytes[1] = (byte) 0xCA
            mybytes[2] = (byte) 0x00
            mybytes[3] = (byte) 0x00
            mybytes[4] = (byte) 0x00
            TerminalFactory factory = TerminalFactory.getDefault();
            List<CardTerminal> terminals = factory.terminals().list();
            // get the first terminal
            CardTerminal terminal = terminals.get(0);
            terminal.waitForCardPresent(0);
            // establish a connection with the card
            Card c = terminal.connect("T=0");
            CardChannel cc = c.getBasicChannel();
            ResponseAPDU answer = cc.transmit(new CommandAPDU(mybytes));
            byte[] reponseBytesArr = answer.getBytes();
            StringBuilder sb = new StringBuilder();
            for (int i = 0; i < reponseBytesArr.length; i++) {
                byte b = reponseBytesArr[i];
                if (i <= reponseBytesArr.length - 3) {
                    // append uid
                    sb.append(String.format("%02X", b));
//                    sb.append(String.format("%02X ", b));
                }
            }
            System.out.println("UID: " + sb.toString());
            return sb.toString()
        } catch(Exception e){
            log.info("No card present: " + e)
//            println e
        }
        return null
    }

}
