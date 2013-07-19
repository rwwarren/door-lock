import javax.smartcardio.*;
//Does the interactions with the card
//and makes the changes and connections
import java.util.List;

public class NFCcard {

  private TerminalFactory factory;
  //private TerminalFactory factory = TerminalFactory.getDefault();
  private List<CardTerminal> terminals;// = factory.terminals().list();
  private CardTerminal terminal;// = terminals.get(0);
  private Card card;

  public NFCcard() throws CardException{
    factory = TerminalFactory.getDefault();
    terminals = factory.terminals().list();
    terminal = terminals.get(0);

  }

  public void connectCard() throws CardException{
    //factory = TerminalFactory.getDefault();
    //terminals = factory.terminals().list();
    //terminal = terminals.get(0);
    card = terminal.connect("T=0");

  }

  public void disconnectCard() throws CardException{

  }

}
