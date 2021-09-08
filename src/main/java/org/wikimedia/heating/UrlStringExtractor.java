package org.wikimedia.heating;

import java.io.IOException;
import java.io.InputStream;
import java.net.MalformedURLException;
import java.net.URL;

public class UrlStringExtractor {

    private final URL url;
    private final int length;

    public UrlStringExtractor(String url, int length) throws MalformedURLException {
        this.url = new URL(url);
        this.length = length;
    }

    public String readString() throws IOException {
        URL url = this.url;
        try (InputStream is = url.openStream()) {
            byte[] tempBuffer = new byte[this.length];
            is.read(tempBuffer);
            return new String(tempBuffer);
        }
    }
}
