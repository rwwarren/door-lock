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
    private byte[] atr = null;
    private String protocol = null;
    private byte[] historical = null;

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
            App pcsc = new App();
//            PCSC pcsc = new PCSC();
            TerminalFactory factory = TerminalFactory.getDefault();
            List<CardTerminal> terminals = factory.terminals().list();

            System.out.println("Terminals: " + terminals);
            // get the first terminal
            CardTerminal terminal = terminals.get(0);
            terminal.waitForCardPresent(0);
            // establish a connection with the card
//            Card c = terminal.connect("DIRECT");
            Card c = terminal.connect("T=0");
//            Card c = pcsc.establishConnection(terminal);
            CardChannel cc = c.getBasicChannel();
            ResponseAPDU answer = cc.transmit(new CommandAPDU(mybytes));
//            ResponseAPDU answer = cc.transmit( new CommandAPDU(new byte[] { (byte)0xFF, (byte)0xCA, (byte)0x00, (byte)0x00, (byte)0x00 } ));

//            System.out.println("answer: " + answer.toString());

            byte[] reponseBytesArr = answer.getBytes();

//            System.out.println("answer byte[]");
            StringBuilder sb = new StringBuilder();

            for (int i = 0; i < reponseBytesArr.length; i++) {
                //print arr
                byte b = reponseBytesArr[i];
//                System.out.println(b);
                if (i <= reponseBytesArr.length - 3) {
                    // append uid
                    sb.append(String.format("%02X ", b));
                }
            }
            System.out.println("UID: " + sb.toString());
        } catch(Exception e){
            println e
        }

    }

//    def static attemptNFCTool() {
//        try {
//            //get UID cmd apdu
//            def mybytes = new byte[5]
//            mybytes[0] = (byte) 0xFF
//            mybytes[1] = (byte) 0xCA
//            mybytes[2] = (byte) 0x00
//            mybytes[3] = (byte) 0x00
//            mybytes[4] = (byte) 0x00
//            App pcsc = new App();
////            PCSC pcsc = new PCSC();
//            CardTerminal ct = pcsc.selectCardTerminal();
//            Card c = null;
//            if (ct == null) {
//                throw new Exception("ct null")
//            }
//            c = pcsc.establishConnection(ct);
//            CardChannel cc = c.getBasicChannel();
//            ResponseAPDU answer = cc.transmit(new CommandAPDU(mybytes));
////            ResponseAPDU answer = cc.transmit( new CommandAPDU(new byte[] { (byte)0xFF, (byte)0xCA, (byte)0x00, (byte)0x00, (byte)0x00 } ));
//
//            System.out.println("answer: " + answer.toString());
//
//            byte[] reponseBytesArr = answer.getBytes();
//
//            System.out.println("answer byte[]");
//            StringBuilder sb = new StringBuilder();
//
//            for (int i = 0; i < reponseBytesArr.length; i++) {
//                //print arr
//                byte b = reponseBytesArr[i];
//                System.out.println(b);
//                if (i <= reponseBytesArr.length - 3) {
//                    // append uid
//                    sb.append(String.format("%02X ", b));
//                }
//            }
//            System.out.println("UID: " + sb.toString());
//        } catch(Exception e){
//            println e
//        }
//
//    }

    public CardTerminal selectCardTerminal() {
        try {
            // show the list of available terminals
            TerminalFactory factory = TerminalFactory.getDefault();
            List<CardTerminal> terminals = factory.terminals().list();
            ListIterator<CardTerminal> terminalsIterator = terminals
                    .listIterator();
            CardTerminal terminal = null;
            CardTerminal defaultTerminal = null;
            if (terminals.size() > 1) {
                System.out
                        .println("Please choose one of these card terminals (1-"
                        + terminals.size() + "):");
                int i = 1;
                while (terminalsIterator.hasNext()) {
                    terminal = terminalsIterator.next();
                    System.out.print("[" + i + "] - " + terminal
                            + ", card present: " + terminal.isCardPresent());
                    if (i == 1) {
                        defaultTerminal = terminal;
                        System.out.println(" [default terminal]");
                    } else {
                        System.out.println();
                    }
                    i++;
                }
                Scanner input = new Scanner(System.in);
                try {
                    int option = input.nextInt();
                    terminal = terminals.get(option - 1);
                } catch (Exception e2) {
                    // System.err.println("Wrong value, selecting default terminal!");
                    terminal = defaultTerminal;

                }
                System.out.println("Selected: " + terminal.getName());
                // Console console = System.console();
                return terminal;
            }

        } catch (Exception e) {
            System.err.println("Error occured:");
            e.printStackTrace();
        }
        return null;
    }

    public String byteArrayToHexString(byte[] b) {
        StringBuffer sb = new StringBuffer(b.length * 2);
        for (int i = 0; i < b.length; i++) {
            int v = b[i] & 0xff;
            if (v < 16) {
                sb.append('0');
            }
            sb.append(Integer.toHexString(v));
        }
        return sb.toString().toUpperCase();
    }

    public static byte[] hexStringToByteArray(String s) {
        int len = s.length();
        byte[] data = new byte[len / 2];
        for (int i = 0; i < len; i += 2) {
            data[i / 2] = (byte) ((Character.digit(s.charAt(i), 16) << 4) + Character
                    .digit(s.charAt(i + 1), 16));
        }
        return data;
    }

    public Card establishConnection(CardTerminal ct) {
        this.atr = null;
        this.historical = null;
        this.protocol = null;

        System.out
                .println("To establish connection, please choose one of these protocols (1-4):");
        System.out.println("[1] - T=0");
        System.out.println("[2] - T=1");
        System.out.println("[3] - T=CL");
        System.out.println("[4] - * [default]");

        String p = "*";
        Scanner input = new Scanner(System.in);

        try {
            int option = input.nextInt();

            if (option == 1)
                p = "T=0";
            if (option == 2)
                p = "T=1";
            if (option == 3)
                p = "T=CL";
            if (option == 4)
                p = "*";
        } catch (Exception e) {
            // System.err.println("Wrong value, selecting default protocol!");
            p = "*";
        }

        System.out.println("Selected: " + p);

        Card card = null;
        try {
            card = ct.connect(p);
        } catch (CardException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
            return null;
        }
        ATR atr = card.getATR();
        System.out.println("Connected:");
        System.out.println(" - ATR:  " + byteArrayToHexString(atr.getBytes()));
        System.out.println(" - Historical: "
                + byteArrayToHexString(atr.getHistoricalBytes()));
        System.out.println(" - Protocol: " + card.getProtocol());

        this.atr = atr.getBytes();
        this.historical = atr.getHistoricalBytes();
        this.protocol = card.getProtocol();

        return card;

    }
}
