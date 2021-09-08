package org.wikimedia.heating;

import static java.lang.Integer.parseInt;

import java.io.IOException;
import java.net.MalformedURLException;
import java.util.Calendar;

public class ScheduleSource {
    private final String baseUrl;
    private final UrlStringExtractor endHourExtractor;
    private final UrlStringExtractor startHourExtractor;

    public ScheduleSource(String baseUrl) throws MalformedURLException {
        this.baseUrl = baseUrl;
        endHourExtractor = new UrlStringExtractor(baseUrl + "/end", 2);
        startHourExtractor = new UrlStringExtractor(baseUrl + "/start", 2);
    }

    public boolean isWithinWorkingHours() throws IOException {
        int thisHour = Calendar.getInstance().get(Calendar.HOUR_OF_DAY);
        return thisHour > startHour() && thisHour < endHour();
    }

    public int endHour() throws NumberFormatException, IOException {
        return parseInt(endHourExtractor.readString());
    }

     public int startHour() throws NumberFormatException, IOException {
        return parseInt(startHourExtractor.readString());
    }
}
