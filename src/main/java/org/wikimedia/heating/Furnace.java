package org.wikimedia.heating;

import java.io.IOException;
import java.io.OutputStream;
import java.net.Socket;

public class Furnace {

    private final String host;
    private final int port;

    public Furnace(String host, int port) {
        this.host = host;
        this.port = port;
    }

    public void switchOff() throws IOException {
        sendCommand("off");
    }

    public void switchOn() throws IOException {
        sendCommand("on");
    }

    // TODO: transport should be further extracted to its own class
    private void sendCommand(String on) throws IOException {
        try (Socket socket = new Socket(host, port)) {
            OutputStream os = socket.getOutputStream();
            os.write(on.getBytes());
            os.flush();
            os.close();
        }
    }
}
