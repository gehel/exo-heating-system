package org.wikimedia.heating;

import java.io.IOException;
import java.io.OutputStream;
import java.net.Socket;
import java.net.UnknownHostException;

public class HeatingManagerImpl {

    public void manageHeating(String t, String threshold, boolean active) {
        double dT = new Double(t);
        double dThreshold = new Double(threshold);
        if (dT < dThreshold && active) {
            try {
                Socket socket = new Socket("heater.home", 9999);
                OutputStream os = socket.getOutputStream();
                os.write("on".getBytes());
                os.flush();
                os.close();
                socket.close();
            } catch (UnknownHostException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        } else if (dT > dThreshold && active) {
            try {
                Socket socket = new Socket("heater.home", 9999);
                OutputStream os = socket.getOutputStream();
                os.write("off".getBytes());
                os.flush();
                os.close();
                socket.close();
            } catch (UnknownHostException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

}
