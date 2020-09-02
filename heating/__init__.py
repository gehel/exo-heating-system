import datetime
import socket
import requests

# The system obtains temperature data from a remote source,
# compares it with a given threshold and controls a remote heating
# unit by switching it on and off. It does so only within a time
# period configured on a remote service (or other source)
#
# This is purpose-built crap


class HeatingManagerImpl:
    def manage_heating(self, t: str, threshold: str, active: bool):
        f_t = float(t)
        f_threshold = float(threshold)
        if f_t < f_threshold and active:
            try:
                s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                s.connect(('heater.home', 9999))
                s.sendall(b'on')
                s.close()
            except Exception:
                print('error connecting')
        elif f_t > f_threshold and active:
            try:
                s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                s.connect(('heater.home', 9999))
                s.sendall(b'off')
                s.close()
            except Exception:
                print('error connecting')


class ScheduleManager:
    def manage(self, h_m: HeatingManagerImpl, threshold: str):
        t = self.string_from_url("http://probe.home:9990/temp", 4)
        if datetime.datetime.now().time().hour > self.start_hour() and datetime.datetime.now().time().hour < self.end_hour():
            h_m.manage_heating(t, threshold, True)
        if datetime.datetime.now().time().hour < self.start_hour() or datetime.datetime.now().time().hour > self.end_hour():
            h_m.manage_heating(t, threshold, False)

    def end_hour(self) -> int:
        return int(self.string_from_url("http://timer.home:9990/end", 2))

    def string_from_url(self, url: str, s: int) -> str:
        r = requests.get(url)
        return r.text[:s]

    def start_hour(self) -> int:
        return int(self.string_from_url("http://timer.home:9990/start", 2))
