package com.wrixton.doorlock

import org.springframework.beans.factory.annotation.Value
import org.springframework.context.ApplicationContext
import org.springframework.context.annotation.Bean
import org.springframework.context.annotation.Configuration
import org.springframework.context.annotation.PropertySource
import org.springframework.context.annotation.PropertySources
import org.springframework.context.support.ClassPathXmlApplicationContext
import org.springframework.context.support.PropertySourcesPlaceholderConfigurer
import org.springframework.stereotype.Component


import javax.smartcardio.*;
import java.util.*
import org.apache.logging.log4j.Logger;
import org.apache.logging.log4j.LogManager;

/**
 * NFC Application for the doorlock
 */
@Component
//@ComponentScan(basePackages = { "com.wrixton.*" })
//@Configuration
//@PropertySource("classpath:application.properties")
//@PropertySource(value = "properties/api.properties", ignoreResourceNotFound = false)
public class App {
    private static final Logger logger = LogManager.getLogger(App.class)

    private static ApiClient apiClient

//    @Value('${api.url}')
    private static String url

    public App(){
        //todo spring addition
        apiClient = new ApiClient("http://api.localhost", "testing")
    }

    public static void main(String[] args) throws CardException {
//        ApplicationContext context = new ClassPathXmlApplicationContext("applicationContext.xml")

        System.out.println("Hello World! Main NFC Application")
        logger.info 'Simple sample to show log field is injected.'
        logger.info 'here is the url: ' + url
        apiClient = new ApiClient("http://api.localhost", "testing")
        //TODO add some waiting for the card and the terminal factory?
        for(;;){
            def uid = attemptNFCTool()
            if(uid != null && uid.length() > 0){
                changeLock(uid)
            }
            sleep(1000)
        }
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
                }
            }
            println "Card read and waiting for disconnect"
            terminal.waitForCardAbsent(0)
            System.out.println("UID: " + sb.toString())
            return sb.toString()
        } catch(Exception e){
            logger.info("No card present: " + e)
        }
        return null
    }

}
