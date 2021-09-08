package org.wikimedia.heating;

import static java.lang.Double.parseDouble;

import java.io.IOException;
import java.net.MalformedURLException;

public class TemperatureProbe {

    private UrlStringExtractor urlStringExtractor;

    public TemperatureProbe(String url) throws MalformedURLException {
        urlStringExtractor = new UrlStringExtractor(url, 4);
    }

    public double read() throws IOException {
        return parseDouble(urlStringExtractor.readString());
    }
}
