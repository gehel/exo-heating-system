package org.wikimedia.heating;

import java.io.IOException;

public class Thermostat {

    private final TemperatureProbe temperatureProbe;
    private final TemperatureSettings temperatureSettings;
    private Furnace furnace;

    public Thermostat(Furnace furnace, TemperatureProbe temperatureProbe, TemperatureSettings temperatureSettings) {
        this.furnace = furnace;
        this.temperatureProbe = temperatureProbe;
        this.temperatureSettings = temperatureSettings;
    }

    public void tick() throws IOException {
        double currentTemperature = temperatureProbe.read();
        double targetTemperature = temperatureSettings.getTargetTemperature();

        if (currentTemperature < targetTemperature) {
            furnace.switchOn();
        } else if (currentTemperature > targetTemperature) {
            furnace.switchOff();
        }
    }

}
