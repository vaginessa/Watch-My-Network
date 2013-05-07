<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="tr-TR">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>watchMyNetwork v0.0.1</title>
        <link rel="stylesheet" media="all" href="css/cssStyle.css" type="text/css" />
        <script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
        <script src="js/javascriptShell.js" type="text/javascript"></script>
        <script src="js/autoresize.jquery.js" type="text/javascript"></script>
    </head>
    <body >
        <div id="container">
            <div id="header">
                <a name="top"></a>
                <div class="link home">Home</div>
                <div class="link about">About</div>
            </div>
            <div id="content"></div>
            <div id="wait">
                <img src="./img/ajax-loader.gif"/>
            </div>
        </div>

        <div style="display:none;">
            <div id="homeData">
                <div id="home">
                    <a href="#" alt="type=browsingHistory">Browsing History</a>
                    <a href="#" onclick="process('newDeviceControl'); return false;">New Device Control</a>
                    <a href="#" onclick="process('ipMacChanging'); return false;">IP - MAC Changing</a>
                    <a href="#" onclick="process('vlanData'); return false;">Vlan Search</a>
                    <a href="#" onclick="process('osData'); return false;">Operating System Search</a>
                </div>
            </div>
            <div id="aboutData">
                <div id="about" style="text-align:center;margin-top:245px;">

Watch My Network v0.0.1<br />
----------------------------------<br />
BSD Licence<br /><br />

Veysi Ertekin <<a href="mailto:veysi.ertekin123@gmail.com">veysi.ertekin123@gmail.com</a>>

                </div>
            </div>
            <div id="newDeviceData">
                <div style="text-align:center;margin-top:255px;">
                    <form id="newDeviceForm" action="#" method="post">
                        Within
                        <select name="time">
                            <option value="01h"> 1 hour </option>
                            <option value="01d"> 1 day</option>
                            <option value="10d"> 10 days</option>
                            <option value="01m">  1 month</option>
                            <option value="06m">  6 months</option>
                        </select>
                        <input type="hidden" name="type" value="newDeviceControl" />
                        <input type="button" value="[[:Device list:]]" />
                    </form>
                </div>
            </div>
            <div id="ipMacChanging">
                <div style="text-align:center;margin-top:255px;">
                    <form id="ipMacChanging" action="#" method="post">
                        <input type="checkbox" name="ip" value="ip" checked="checked"/>IPs
                        &nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="mac" value="mac" />MACs
                        &nbsp;&nbsp;&nbsp;
                        <select name="list">
                            <option />
                        </select>
                        <input type="hidden" name="type" value="ipMacChanging" />
                        <input type="button" value="[[:Change List:]]" />
                    </form>
                </div>
            </div>
            <div id="vlanData">
                <div style="text-align:center;margin-top:255px;">
                    <form id="vlanData" action="#" method="post">
                        Please, Enter IP range :

                        <input type="text" name="ip1" sira="1" value="192" />.
                        <input type="text" name="ip2" sira="2" value="168" />.
                        <input type="text" name="ip3" sira="3" value="1" />.
                        <input type="text" name="ip4" sira="4" value="1" />
                        /
                        <input type="text" name="ip5" sira="5" value="192" />.
                        <input type="text" name="ip6" sira="6" value="168" />.
                        <input type="text" name="ip7" sira="7" value="1" />.
                        <input type="text" name="ip8" sira="8" value="1" />

                        <input type="hidden" name="type" value="vlanData" />
                        <input type="button" value=" List " />
                    </form>
                </div>
            </div>
            <div id="osData">
                <div style="text-align:center;margin-top:235px;">
                    <form id="osData" action="#" method="post">
                        Operating System :

                        <select name="os1">
                            <option value="Linux">Linux</option>
                            <option value="Windows">Windows</option>
                            <option value="OSX">OSX</option>
                        </select>
                        or
                        <input type="text" name="os2" value="" />

                        <input type="hidden" name="type" value="osData" />
                        <input type="button" value=" List " />
                    </form>
                    ____________________________________________________
                    <br />
                    <br />
                    <form id="osData2" action="#" method="post">
                        <input type="hidden" name="different" value="different" />
                        <input type="hidden" name="type" value="osData" />
                        <input type="button" value=" Different OSs List " />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
