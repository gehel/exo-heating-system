package org.wikimedia.heating;

import java.net.MalformedURLException;

/**
 * The system obtains temperature data from a remote source,
 * compares it with a given threshold and controls a remote heating
 * unit by switching it on and off. It does so only within a time
 * period configured on a remote service (or other source)
 * 
 * This is purpose-built crap
 *
 */
public class ScheduleManager {

	private static final TemperatureSettings temperatureSettings = new TemperatureSettings();

	/**
	 * This method is the entry point into the code. You can assume that it is
	 * called at regular interval with the appropriate parameters.
	 */
	public static void manage(Thermostat hM, String threshold) throws Exception {
		Double targetTemperature = new Double(threshold);
		ScheduleSource scheduleSource = new ScheduleSource("http://timer.home:9990");

		if (scheduleSource.isWithinWorkingHours()) {
			temperatureSettings.setTargetTemperature(targetTemperature);
			hM.tick();
		}
	}


	// let's assume that the caller of ScheduleManager.manage() uses this method or similar to create the
	// thermostat
	public static Thermostat createThermostat() throws MalformedURLException {
		TemperatureProbe temperatureProbe = new TemperatureProbe("http://probe.home:9990/temp");
		Furnace furnace = new Furnace("heater.home", 9999);
		return new Thermostat(furnace, temperatureProbe, temperatureSettings);
	}

}
