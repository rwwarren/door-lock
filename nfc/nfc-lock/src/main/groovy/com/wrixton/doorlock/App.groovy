package com.wrixton.doorlock

import org.springframework.beans.factory.annotation.Value

//import org.nfctools.scio.TerminalMode

import javax.smartcardio.*;
//import com.acs.smartcard.Reader;
import java.util.*
import org.apache.logging.log4j.Logger;
import org.apache.logging.log4j.LogManager;



/**
 * NFC Application for the doorlock
 */
public class App {
    private static final Logger logger = LogManager.getLogger(App.class)



    //TODO move this to groovy!
    //TODO move it all to groovy
//    private byte[] atr = null;
//    private String protocol = null;
//    private byte[] historical = null;

    private static ApiClient apiClient

//    @Value("${api.url}")
    private String url

    public App(){
        //todo spring addition
        apiClient = new ApiClient("http://api.localhost", "testing")
    }

    public static void main( String[] args ) throws CardException {
        System.out.println("Hello World! Main NFC Application")
        logger.info 'Simple sample to show log field is injected.'

//        App myApp = new App()
        apiClient = new ApiClient("http://api.localhost", "testing")
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

    private static void changeLock(def uid){
        LOCK_STATUS lockStatus = apiClient.lockStatus(uid)
        def response
        if(lockStatus == LOCK_STATUS.LOCKED){
            response = apiClient.unlock(uid)
        } else if (lockStatus == LOCK_STATUS.UNLOCKED){
            response = apiClient.lock(uid)

        } else {
            throw new IllegalStateException("Lock is is illegal state: " + lockStatus)
        }
        if(response.success == "1"){
            println "Successful change"
        } else {
            println "Change Failed!!!!!!"
        }
    }

    private static boolean checkValidUser(){
        return apiClient.isValidUser()
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
            TerminalFactory factory = TerminalFactory.getDefault()
            List<CardTerminal> terminals = factory.terminals().list()
            // get the first terminal
            CardTerminal terminal = terminals.get(0)
            println "Waiting for card to connect"
            terminal.waitForCardPresent(0);
            // establish a connection with the card
            Card c = terminal.connect("T=0")
            CardChannel cc = c.getBasicChannel()
            ResponseAPDU answer = cc.transmit(new CommandAPDU(mybytes))
            byte[] reponseBytesArr = answer.getBytes()
            StringBuilder sb = new StringBuilder()
            for (int i = 0; i < reponseBytesArr.length; i++) {
                byte b = reponseBytesArr[i]
                if (i <= reponseBytesArr.length - 3) {
                    // append uid
                    sb.append(String.format("%02X", b))
//                    sb.append(String.format("%02X ", b));
                }
            }
            println "Card read and waiting for disconnect"
            terminal.waitForCardAbsent(0)
            System.out.println("UID: " + sb.toString())
            return sb.toString()
        } catch(Exception e){
            logger.info("No card present: " + e)
//            log.info("No card present: " + e)
//            println e
        }
        return null
    }

}
