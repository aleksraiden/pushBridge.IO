<?php
/**
 * MRIM.php
 * 
 * PHP Mail.Ru Agent protocol implementation
 * 
 * @copyright  (c) Eugen, 2009
 * @author     Eugen <eu_gen@mail.ru>
 * @version    3.0 build 1080
 * @link       http://releases.eugen.su/MRIM.PHP/v3/class.mrim.phps
 * @since      File available since version 3.0 build 1000
 */
class mrim
{
    /**
     * MRIM port
     * 
     * @var int
     */
    var $port = 443;
    /**
     * MRIM server
     * 
     * @var string
     */
    var $server = 'mrim.mail.ru';
    /**
     * Class behaviour
     * 1 - UNIX
     * 2 - Other
     * 
     * @var int
     */
    var $behaviour = 1;
    /**
     * Turn filetransfer functions on/off
     * 
     * @var bool
     */
    var $bind_cn = true;
    /**
     * Ping timeout (for behaviour=1 and single-thread mode)
     * 
     * @var int
     */
    var $ping_time = 30;
    /**
     * Connection timeout (accept timeout) for all (incoming and outgoing) connections
     * 
     * @var int
     */
    var $timeout = 5;
    /**
     * My login
     * 
     * @var string
     */
    var $email = '';
    /**
     * My MRIM nickname
     * 
     * @var string
     */
    var $nickname = '';
    /**
     * User-Agent
     * 
     * @var string
     */
    var $user_agent = 'MRIM class v3.0 (build 1080);';
    /**
     * Client description
     * 
     * @var string
     */
    var $user_agent_descr = 'client="MRIM.PHP" version="3.0" build="1080"';
    var $sock;
    var $cn1;
    var $cn2;
    var $mnumb = 0;
    var $groups = 0;
    var $password;
    var $proxy;
    var $status;
    var $xstatus;
    var $xstatus_text;
    var $proxy_port;
    var $proxy_type;
    var $proxy_user;
    var $proxy_pass;
    var $ip;
    var $base;
    var $CS_MAGIC = 0xDEADBEEF;
    var $PROTO_VERSION = 0x00010015;
    var $CONTACT_FLAG_GROUP = 0x02;
    var $CONTACT_FLAG_IGNORE = 0x10;
    var $CONTACT_FLAG_INVISIBLE = 0x04;
    var $CONTACT_FLAG_REMOVED = 0x01;
    var $CONTACT_FLAG_SHADOW = 0x20;
    var $CONTACT_FLAG_SMS = 0x100000;
    var $CONTACT_FLAG_VISIBLE = 0x08;
    var $CONTACT_INTFLAG_NOT_AUTHORIZED = 0x01;
    var $CONTACT_OPER_ERROR = 0x01;
    var $CONTACT_OPER_GROUP_LIMIT = 0x6;
    var $CONTACT_OPER_INTERR = 0x02;
    var $CONTACT_OPER_INVALID_INFO = 0x04;
    var $CONTACT_OPER_NO_SUCH_USER = 0x03;
    var $CONTACT_OPER_SUCCESS = 0x00;
    var $CONTACT_OPER_USER_EXISTS = 0x05;
    var $FILE_TRANSFER_MIRROR = 4;
    var $FILE_TRANSFER_STATUS_DECLINE = 0;
    var $FILE_TRANSFER_STATUS_ERROR = 2;
    var $FILE_TRANSFER_STATUS_INCOMPATIBLE_VERS = 3;
    var $FILE_TRANSFER_STATUS_OK = 1;
    var $GET_CONTACTS_ERROR = 0x01;
    var $GET_CONTACTS_INTERR = 0x02;
    var $GET_CONTACTS_OK = 0x00;
    var $LOGOUT_NO_RELOGIN_FLAG = 0x10;
    var $MAX_CLIENT_DESCRIPTION = 256;
    var $MESSAGE_DELIVERED = 0x00;
    var $MESSAGE_FLAG_ALARM = 0x4000;
    var $MESSAGE_FLAG_AUTHORIZE = 0x08;
    var $MESSAGE_FLAG_CONTACT = 0x0200;
    var $MESSAGE_FLAG_MULTICAST = 0x1000;
    var $MESSAGE_FLAG_NORECV = 0x04;
    var $MESSAGE_FLAG_NOTIFY = 0x0400;
    var $MESSAGE_FLAG_OFFLINE = 0x01;
    var $MESSAGE_FLAG_OLD = 0x200000;
    var $MESSAGE_FLAG_RTF = 0x80;
    var $MESSAGE_FLAG_SMS = 0x0800;
    var $MESSAGE_FLAG_SMS_NOTIFY = 0x2000;
    var $MESSAGE_FLAG_SPAM = 0x010000;
    var $MESSAGE_FLAG_SYSTEM = 0x40;
    var $MESSAGE_FLAG_UNI = 0x100000;
    var $MESSAGE_REJECTED_DENY_OFFMSG = 0x8006;
    var $MESSAGE_REJECTED_INTERR = 0x8003;
    var $MESSAGE_REJECTED_LIMIT_EXCEEDED = 0x8004;
    var $MESSAGE_REJECTED_NOUSER = 0x8001;
    var $MESSAGE_REJECTED_TOO_LARGE = 0x8005;
    var $MESSAGE_USERFLAGS_MASK = 0x36A8;
    var $MRIM_ANKETA_INFO_STATUS_DBERR = 2;
    var $MRIM_ANKETA_INFO_STATUS_NOUSER = 0;
    var $MRIM_ANKETA_INFO_STATUS_OK = 1;
    var $MRIM_ANKETA_INFO_STATUS_RATELIMERR = 3;
    var $MRIM_CS_ADD_CONTACT = 0x1019;
    var $MRIM_CS_ADD_CONTACT_ACK = 0x101A;
    var $MRIM_CS_ANKETA_INFO = 0x1028;
    var $MRIM_CS_AUTHORIZE = 0x1020;
    var $MRIM_CS_AUTHORIZE_ACK = 0x1021;
    var $MRIM_CS_CHANGE_STATUS = 0x1022;
    var $MRIM_CS_CONNECTION_PARAMS = 0x1014;
    var $MRIM_CS_CONTACT_LIST2 = 0x1037;
    var $MRIM_CS_DELETE_OFFLINE_MESSAGE = 0x101E;
    var $MRIM_CS_FILE_TRANSFER = 0x1026;
    var $MRIM_CS_FILE_TRANSFER_ACK = 0x1027;
    var $MRIM_CS_GET_MPOP_SESSION = 0x1024;
    var $MRIM_CS_HELLO = 0x1001;
    var $MRIM_CS_HELLO_ACK = 0x1002;
    var $MRIM_CS_LOGIN_ACK = 0x1004;
    var $MRIM_CS_LOGIN_REJ = 0x1005;
    var $MRIM_CS_LOGIN2 = 0x1038;
    var $MRIM_CS_LOGOUT = 0x1013;
    var $MRIM_CS_MAILBOX_STATUS = 0x1033;
    var $MRIM_CS_MESSAGE = 0x1008;
    var $MRIM_CS_MESSAGE_ACK = 0x1009;
    var $MRIM_CS_MESSAGE_RECV = 0x1011;
    var $MRIM_CS_MESSAGE_STATUS = 0x1012;
    var $MRIM_CS_MICROBLOG_POST = 0x1064;
    var $MRIM_CS_MICROBLOG_RECV = 0x1063;
    var $MRIM_CS_MODIFY_CONTACT = 0x101B;
    var $MRIM_CS_MODIFY_CONTACT_ACK = 0x101C;
    var $MRIM_CS_MPOP_SESSION = 0x1025;
    var $MRIM_CS_NEW_EMAIL = 0x1048;
    var $MRIM_CS_OFFLINE_MESSAGE_ACK = 0x101D;
    var $MRIM_CS_PING = 0x1006;
    var $MRIM_CS_SMS = 0x1039;
    var $MRIM_CS_SMS_ACK = 0x1040;
    var $MRIM_CS_USER_INFO = 0x1015;
    var $MRIM_CS_USER_STATUS = 0x100F;
    var $MRIM_CS_WP_REQUEST = 0x1029;
    var $MRIM_CS_WP_REQUEST_PARAM_BIRTHDAY = 6;
    var $MRIM_CS_WP_REQUEST_PARAM_BIRTHDAY_DAY = 14;
    var $MRIM_CS_WP_REQUEST_PARAM_BIRTHDAY_MONTH = 13;
    var $MRIM_CS_WP_REQUEST_PARAM_CITY_ID = 11;
    var $MRIM_CS_WP_REQUEST_PARAM_COUNTRY_ID = 15;
    var $MRIM_CS_WP_REQUEST_PARAM_DATE1 = 7;
    var $MRIM_CS_WP_REQUEST_PARAM_DATE2 = 8;
    var $MRIM_CS_WP_REQUEST_PARAM_DOMAIN = 1;
    var $MRIM_CS_WP_REQUEST_PARAM_FIRSTNAME = 3;
    var $MRIM_CS_WP_REQUEST_PARAM_LASTNAME = 4;
    var $MRIM_CS_WP_REQUEST_PARAM_MAX = 16;
    var $MRIM_CS_WP_REQUEST_PARAM_NICKNAME = 2;
    var $MRIM_CS_WP_REQUEST_PARAM_ONLINE = 9;
    var $MRIM_CS_WP_REQUEST_PARAM_SEX = 5;
    var $MRIM_CS_WP_REQUEST_PARAM_STATUS = 10;
    var $MRIM_CS_WP_REQUEST_PARAM_USER = 0;
    var $MRIM_CS_WP_REQUEST_PARAM_ZODIAC = 12;
    var $MRIM_GET_SESSION_FAIL = 0;
    var $MRIM_GET_SESSION_SUCCESS = 1;
    var $PARAM_VALUE_LENGTH_LIMIT = 64;
    var $PARAMS_NUMBER_LIMIT = 50;
    var $SMS_ACK_DELIVERY_STATUS_INVALID_PARAMS = 0x10000;
    var $SMS_ACK_DELIVERY_STATUS_SUCCESS = 1;
    var $SMS_ACK_SERVICE_UNAVAILABLE = 2;
    var $STATUS_AWAY = 0x02;
    var $STATUS_FLAG_INVISIBLE = 0x80000000;
    var $STATUS_OFFLINE = 0x00;
    var $STATUS_ONLINE = 0x01;
    var $STATUS_OTHER = 0x04;
    var $STATUS_UNDETERMINATED = 0x03;
    /**
     * mrim::mrim()
     * 
     * Constructor. Sets ERROR_REPORTING to 0, MAX_EXECUTION_TIME to unlimited and turns off MAGIC_QUOTES_RUNTIME
     */
    function mrim()
    {
        error_reporting(0);
        set_time_limit(0);
        ini_set('magic_quotes_runtime','off');
    }
    /**
     * mrim::make_packet()
     * 
     * Makes ready to send MRIM packets
     * 
     * @access private
     * @param int $msg
     * @param string $data
     * @return string
     */
    function make_packet($msg,$data = '')
    {
        $dlen = ($data?strlen($data):0);
        $mrim_packet = pack('V*',$this->CS_MAGIC,$this->PROTO_VERSION,$this->mnumb,$msg,$dlen,0,0,0,0,0,0);
        if($data)
            $mrim_packet .= $data;
        if($this->mnumb == 0xFFFFFFFF)
            $this->mnumb = 0;
        else
            $this->mnumb++;
        return $mrim_packet;
    }
    /**
     * mrim::connect()
     * 
     * Tries to establish connection to MRIM server (using proxy-server or single connection)
     * 
     * @access public
     * @param string $proxy
     * @param int $port
     * @param string $user
     * @param string $pass
     * @param string $type
     * @return bool
     */
    function connect($proxy = null,$port = null,$user = null,$pass = null,$type = null)
    {
        $this->proxy_type = $type;
        $this->proxy_user = $user;
        $this->proxy_pass = $pass;
        if(function_exists('stream_socket_server') && $this->behaviour == 1 && $this->bind_cn) {
            $this->cn1 = stream_socket_server('tcp://0.0.0.0:2041');
            $this->cn2 = stream_socket_server('tcp://0.0.0.0:443');
        }
        if(!$proxy || !$port) {
            $this->proxy = null;
            $this->proxy_port = null;
            return $this->connect_single();
        }
        $this->proxy = $proxy;
        $this->proxy_port = $port;
        if($type == 'http')
            return $this->connect_http_proxy($proxy,$port,$user,$pass);
        elseif($type == 'socks4')
            return $this->connect_socks4_proxy($proxy,$port,$user);
        elseif($type == 'socks5')
            return $this->connect_socks5_proxy($proxy,$port,$user,$pass);
        else {
            if($this->connect_http_proxy($proxy,$port,$user,$pass)) {
                $this->proxy_type = 'http';
                return true;
            }
            if($this->connect_socks5_proxy($proxy,$port,$user,$pass)) {
                $this->proxy_type = 'socks5';
                return true;
            }
            if($this->connect_socks4_proxy($proxy,$port,$user)) {
                $this->proxy_type = 'socks4';
                return true;
            }
        }
        return false;
    }
    /**
     * mrim::connect_single()
     * 
     * Tries to establish single connection to MRIM server
     * 
     * @access private
     * @return bool
     */
    function connect_single()
    {
        $this->sock = fsockopen($this->server,$this->port,$en,$es,$this->timeout);
        if(!$this->sock) {
            return false;
        } else {
            $answ = fread($this->sock,20);
            fclose($this->sock);
            list($hostc,$portc) = explode(':',$answ);
            unset($this->sock);
            $this->sock = fsockopen($hostc,intval($portc),$en,$es,$this->timeout);
            if(!$this->sock)
                return false;
            unset($answ);
            $this->mnumb = 0;
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_HELLO));
            $answ = fread($this->sock,48);
            list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4,$data) = unpack('V*',$answ);
            $this->ping_time = $data;
            return true;
        }
    }
    /**
     * mrim::connect_http_proxy()
     * 
     * Tries to establish connection to MRIM server using HTTP proxy
     * 
     * @access private
     * @param string $proxy
     * @param int $port
     * @param string $user
     * @param string $password
     * @return bool
     */
    function connect_http_proxy($proxy,$port,$user = null,$password = null)
    {
        $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
        if(!$this->sock) {
            return false;
        } else {
            if($user && $password)
                $au = "Proxy-Authorization: basic ".base64_encode($user.":".$password)."\r\n";
            else
                $au = '';
            fputs($this->sock,"CONNECT ".$this->server.":".$this->port." HTTP/1.0\r\nHost: ".$this->server.":".$this->port."\r\nUser-Agent: ".$this->user_agent."\r\n".$au."\r\n");
            $code = intval(substr(trim(fgets($this->sock,1024)),9,3));
            if($code != 200) {
                fclose($this->sock);
                return false;
            }
            while(($a = trim(fgets($this->sock,1024)) != ''))
                ;
            $answ = fread($this->sock,20);
            fclose($this->sock);
            list($hostc,$portc) = explode(':',$answ);
            unset($this->sock);
            $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
            fputs($this->sock,"CONNECT ".$hostc.":".intval($portc)." HTTP/1.0\r\nHost: ".$hostc.":".intval($portc)."\r\nUser-Agent: ".$this->user_agent."\r\n".$au."\r\n");
            if(!$this->sock)
                return false;
            $code = intval(substr(trim(fgets($this->sock,1024)),9,3));
            if($code != 200) {
                fclose($this->sock);
                return false;
            }
            while(($a = trim(fgets($this->sock,1024)) != ''))
                ;
            unset($answ);
            $this->mnumb = 0;
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_HELLO));
            $answ = fread($this->sock,48);
            list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4,$data) = unpack('V*',$answ);
            $this->ping_time = $data;
            return true;
        }
    }
    /**
     * mrim::connect_socks4_proxy()
     * 
     * Tries to establish connection to MRIM server using SOCKS4 proxy
     * 
     * @access private
     * @param string $proxy
     * @param int $port
     * @param string $user
     * @return bool
     */
    function connect_socks4_proxy($proxy,$port,$user = null)
    {
        $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
        if(!$this->sock)
            return false;
        else {
            $ip = gethostbyname($this->server);
            if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
            else {
                fclose($this->sock);
                return false;
            }
            $request = pack('C2',0x04,0x01).pack('n1',$this->port).$int.($user)?$user:'0'.pack('C1',0x00);
            fwrite($this->sock,$request);
            $resp = fread($this->sock,9);
            $answer = unpack('Cvn/Ccd',substr($resp,0,2));
            if($answer['vn'] != 0x00) {
                fclose($this->sock);
                return false;
            }
            if($answer['cd'] != 0x5A) {
                fclose($this->sock);
                return false;
            }
            $answ = fread($this->sock,20);
            fclose($this->sock);
            list($hostc,$portc) = explode(':',$answ);
            unset($this->sock);
            $portc = intval($portc);
            $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
            if(!$this->sock)
                return false;
            else {
                $ip = gethostbyname($hostc);
                if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                    $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                else {
                    fclose($this->sock);
                    return false;
                }
                $request = pack('C2',0x04,0x01).pack('n1',$portc).$int.($user)?$user:'0'.pack('C1',0x00);
                fwrite($this->sock,$request);
                $resp = fread($this->sock,9);
                $answer = unpack('Cvn/Ccd',substr($resp,0,2));
                if($answer['vn'] != 0x00) {
                    fclose($this->sock);
                    return false;
                }
                if($answer['cd'] != 0x5A) {
                    fclose($this->sock);
                    return false;
                }
                $this->mnumb = 0;
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_HELLO));
                $answ = fread($this->sock,48);
                list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4,$data) = unpack('V*',$answ);
                $this->ping_time = $data;
                return true;
            }
        }
    }
    /**
     * mrim::connect_socks5_proxy()
     * 
     * Tries to establish connection to MRIM server using SOCKS5 proxy
     * 
     * @access private
     * @param string $proxy
     * @param int $port
     * @param string $user
     * @param string $password
     * @return bool
     */
    function connect_socks5_proxy($proxy,$port,$user = null,$password = null)
    {
        $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
        if(!$this->sock)
            return false;
        else {
            if($user && $password)
                $request = pack('C4',0x05,0x02,0x00,0x02);
            else
                $request = pack('C3',0x05,0x01,0x00);
            fwrite($this->sock,$request);
            $resp = fread($this->sock,3);
            $answer = unpack('Cver/Cmethod',$resp);
            if($answer['method'] == 0x02) {
                $request = pack('C1',0x01).pack('C1',strlen($user)).$user.pack('C1',strlen($password)).$password;
                fwrite($this->sock,$request);
                $resp = fread($this->sock,3);
                $answer = unpack('Cvn/Cresult',$resp);
                if($answer['vn'] != 0x01 && $answer['result'] != 0x00) {
                    fclose($this->sock);
                    return false;
                } else
                    $answer['method'] = 0;
            }
            if($answer['method'] == 0x00) {
                $ip = gethostbyname($this->server);
                if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                    $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                else {
                    fclose($this->sock);
                    return false;
                }
                $request = pack('C4',0x05,0x01,0x00,0x01).$int.pack('n1',$this->port);
                fwrite($this->sock,$request);
                $resp = fread($this->sock,11);
                $answer = unpack('Cver/CREP',substr($resp,0,2));
                if($answer['REP'] != 0x00) {
                    fclose($this->sock);
                    return false;
                }
                $answ = fread($this->sock,20);
                fclose($this->sock);
                list($hostc,$portc) = explode(':',$answ);
                $portc = intval($portc);
                $this->sock = fsockopen($proxy,$port,$en,$es,$this->timeout);
                if(!$this->sock)
                    return false;
                else {
                    if($user && $password)
                        $request = pack('C4',0x05,0x02,0x00,0x02);
                    else
                        $request = pack('C3',0x05,0x01,0x00);
                    fwrite($this->sock,$request);
                    $resp = fread($this->sock,3);
                    $answer = unpack('Cver/Cmethod',$resp);
                    if($answer['method'] == 0x02) {
                        $request = pack('C1',0x01).pack('C1',strlen($user)).$user.pack('C1',strlen($password)).$password;
                        fwrite($this->sock,$request);
                        $resp = fread($this->sock,3);
                        $answer = unpack('Cvn/Cresult',$resp);
                        if($answer['vn'] != 0x01 && $answer['result'] != 0x00) {
                            fclose($this->sock);
                            return false;
                        } else
                            $answer['method'] = 0;
                    }
                    if($answer['method'] == 0x00) {
                        $ip = gethostbyname($hostc);
                        if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                            $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                        else {
                            fclose($this->sock);
                            return false;
                        }
                        $request = pack('C4',0x05,0x01,0x00,0x01).$int.pack('n1',$portc);
                        fwrite($this->sock,$request);
                        $resp = fread($this->sock,11);
                        $answer = unpack('Cver/CREP',substr($resp,0,2));
                        if($answer['REP'] != 0x00) {
                            fclose($this->sock);
                            return false;
                        }
                        $this->mnumb = 0;
                        fwrite($this->sock,$this->make_packet($this->MRIM_CS_HELLO));
                        $answ = fread($this->sock,48);
                        list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4,$data) = unpack('V*',$answ);
                        $this->ping_time = $data;
                        return true;
                    } else {
                        fclose($this->sock);
                        return false;
                    }
                }
                unset($this->sock);
            } else {
                fclose($this->sock);
                return false;
            }
        }
    }
    /**
     * mrim::is_connected()
     * 
     * Checks current connection status
     * 
     * @access public
     * @return bool
     */
    function is_connected()
    {
        if(!is_resource($this->sock) || feof($this->sock))
            return false;
        return true;
    }
    /**
     * mrim::login()
     * 
     * Tries to login on MRIM server
     * 
     * @access public
     * @param string $login
     * @param string $pass
     * @return bool
     */
    function login($login,$pass)
    {
        if(!$this->is_connected())
            return false;
        $st = 'STATUS_INVISIBLE';
        if(!$this->status)
            $this->status = 'invisible';
        $lang = 'ru';
        $login_data = pack('V1',strlen($login)).$login.pack('V1',strlen($pass)).$pass.pack('V2',$this->STATUS_ONLINE | $this->STATUS_FLAG_INVISIBLE,strlen($st)).$st.pack('V4',0x00,0x00,0x03FF,strlen($this->user_agent_descr)).$this->user_agent_descr.pack('V1',strlen($lang)).$lang.pack('V3',0,0,strlen($this->user_agent)).$this->user_agent;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_LOGIN2,$login_data));
        $answ = fread($this->sock,48);
        list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4) = unpack('V*',$answ);
        if($msg == $this->MRIM_CS_LOGIN_ACK) {
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_GET_MPOP_SESSION));
            $this->email = $login;
            $this->password = $pass;
            if($this->behaviour != 1)
                socket_set_blocking($this->sock,0);
            return true;
        }
        return false;
    }
    /**
     * mrim::reconnect()
     * 
     * Fast reconnect to server
     * 
     * @access public
     * @return bool
     */
    function reconnect()
    {
        $this->disconnect();
        $this->connect($this->proxy,$this->proxy_port,$this->proxy_user,$this->proxy_pass,$this->proxy_type);
        if(!$this->is_connected())
            return false;
        if(!$this->login($this->email,$this->password))
            return false;
        $this->set_status($this->status,$this->xstatus,$this->xstatus_text);
        return true;
    }
    /**
     * mrim::message()
     * 
     * Sends plain MRIM message
     * 
     * @access public
     * @param string $to
     * @param string $text
     * @return bool
     */
    function message($to,$text)
    {
        if(!$this->is_connected())
            return false;
        $text = iconv('UTF-8','UTF-16LE//IGNORE',$text);
        $data = pack('V2',$this->MESSAGE_FLAG_OFFLINE,strlen($to)).$to.pack('V1',strlen($text)).$text.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE,$data));
        return $this->mnumb - 1;
    }
    /**
     * mrim::message_auth()
     * 
     * Sends MRIM authorization request
     * 
     * @access public
     * @param string $to
     * @param string $text
     * @return bool
     */
    function message_auth($to,$text = 'Здравствуйте. Пожалуйста, добавьте меня в список ваших контактов.')
    {
        if(!$this->is_connected())
            return false;
        $nickname = ($this->nickname?$this->nickname:$this->email);
        $nickname = iconv('UTF-8','UTF-16LE//IGNORE',$nickname);
        $text = iconv('UTF-8','UTF-16LE//IGNORE',$text);
        $text = base64_encode(pack('V2',2,strlen($nickname)).$nickname.pack('V1',strlen($text)).$text);
        $data = pack('V2',$this->MESSAGE_FLAG_AUTHORIZE,strlen($to)).$to.pack('V1',strlen($text)).$text.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE,$data));
        return $this->mnumb - 1;
    }
    /**
     * mrim::message_alarm()
     * 
     * Send MRIM "ALARM" message
     * 
     * @access public
     * @param string $to
     * @return bool
     */
    function message_alarm($to)
    {
        $text = "Собеседник попытался вас разбудить, однако у вас установлена устаревшая версия Mail.Ru Агента без поддержки функции \"Будильник\". Загрузить самую новую версию вы можете здесь: http://agent.mail.ru/\r\n";
        $text = iconv("UTF-8","UTF-16LE//IGNORE",$text);
        $rtext = '{\\rtf1\\ansi \\u1057?\\u1086?\\u1073?\\u1077?\\u1089?\\u1077?\\u1076?\\u1085?\\u1080?\\u1082? \\u1087?\\u1086?\\u1087?\\u1099?\\u1090?\\u1072?\\u1083?\\u1089?\\u1103? \\u1074?\\u1072?\\u1089? \\u1088?\\u1072?\\u1079?\\u1073?\\u1091?\\u1076?\\u1080?\\u1090?\\u1100?, \\u1086?\\u1076?\\u1085?\\u1072?\\u1082?\\u1086? \\u1091? \\u1074?\\u1072?\\u1089? \\u1091?\\u1089?\\u1090?\\u1072?\\u1085?\\u1086?\\u1074?\\u1083?\\u1077?\\u1085?\\u1072? \\u1091?\\u1089?\\u1090?\\u1072?\\u1088?\\u1077?\\u1074?\\u1096?\\u1072?\\u1103? \\u1074?\\u1077?\\u1088?\\u1089?\\u1080?\\u1103? Mail.Ru \\u1040?\\u1075?\\u1077?\\u1085?\\u1090?\\u1072? \\u1073?\\u1077?\\u1079? \\u1087?\\u1086?\\u1076?\\u1076?\\u1077?\\u1088?\\u1078?\\u1082?\\u1080? \\u1092?\\u1091?\\u1085?\\u1082?\\u1094?\\u1080?\\u1080? "\\u1041?\\u1091?\\u1076?\\u1080?\\u1083?\\u1100?\\u1085?\\u1080?\\u1082?". \\u1047?\\u1072?\\u1075?\\u1088?\\u1091?\\u1079?\\u1080?\\u1090?\\u1100? \\u1089?\\u1072?\\u1084?\\u1091?\\u1102? \\u1085?\\u1086?\\u1074?\\u1091?\\u1102? \\u1074?\\u1077?\\u1088?\\u1089?\\u1080?\\u1102? \\u1074?\\u1099? \\u1084?\\u1086?\\u1078?\\u1077?\\u1090?\\u1077? \\u1079?\\u1076?\\u1077?\\u1089?\\u1100?: http://agent.mail.ru/\\par }';
        $rtext = pack('V2',2,strlen($rtext)).$rtext.pack('V2',4,0xFFFFFF);
        $rtext = base64_encode(gzcompress($rtext,9));
        $data = pack('V2',$this->MESSAGE_FLAG_ALARM | $this->MESSAGE_FLAG_RTF,strlen($to)).$to.pack('V1',strlen($text)).$text.pack('V1',strlen($rtext)).$rtext;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE,$data));
        return $this->mnumb - 1;
    }
    /**
     * mrim::message_notify()
     * 
     * Sends MRIM typing notification
     * 
     * @access public
     * @param string $to
     * @return bool
     */
    function message_notify($to)
    {
        if(!$this->is_connected())
            return false;
        $data = pack('V2',$this->MESSAGE_FLAG_NOTIFY,strlen($to)).$to.pack('V1',1).' '.pack('V1',1).' ';
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE,$data));
        return $this->mnumb - 1;
    }
    /**
     * mrim::message_contact()
     * 
     * Sends contact-list message
     * 
     * @access public
     * @param string $to
     * @param array $text
     * @return bool
     */
    function message_contact($to,$text)
    {
        if(!$this->is_connected())
            return false;
        if(!is_array($text))
            return false;
        $texts = '';
        for($i = 0; $i < count($text); $i++)
            $texts .= $text[$i]['addr'].';'.$text[$i]['name'].';';
        $texts = iconv('UTF-8','UTF-16LE//IGNORE',$texts);
        $data = pack('V2',$this->MESSAGE_FLAG_CONTACT,strlen($to)).$to.pack('V1',strlen($texts)).$texts.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE,$data));
        return $this->mnumb - 1;
    }
    /**
     * mrim::sms()
     * 
     * Sends SMS using MRIM protocol
     * 
     * @access public
     * @param string $to
     * @param mixed $text
     * @return bool
     */
    function sms($to,$text)
    {
        if(!$this->is_connected())
            return false;
        $text = iconv('UTF-8','UTF-16LE//IGNORE',$text);
        $data = pack('V2',0,strlen($to)).$to.pack('V1',strlen($text)).$text;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_SMS,$data));
        return true;
    }
    /**
     * mrim::ping()
     * 
     * Sends PING command to server
     * 
     * @access public
     * @return bool
     */
    function ping()
    {
        if(!$this->is_connected())
            return false;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_PING));
        return true;
    }
    /**
     * mrim::disconnect()
     * 
     * Disconnects from server
     * 
     * @access public
     * @return bool
     */
    function disconnect()
    {
        fclose($this->sock);
        unset($this->sock,$this->mnumb);
        return true;
    }
    /**
     * mrim::authorize()
     * 
     * Accepts authorization request
     * 
     * @access public
     * @param string $email
     * @return bool
     */
    function authorize($email)
    {
        if(!$this->is_connected())
            return false;
        $data = pack('V1',strlen($email)).$email;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_AUTHORIZE,$data));
        return true;
    }
    /**
     * mrim::set_status()
     * 
     * Sets current status and x-status
     * 
     * @access public
     * @param string $status
     * @param string $xstatus
     * @param string $xstatus_text
     * @return bool
     */
    function set_status($status = 'invisible',$xstatus = '',$xstatus_text = '')
    {
        if(!$this->is_connected())
            return false;
        $this->status = $status;
        $this->xstatus = $xstatus;
        $this->xstatus_text = $xstatus_text;
        $status = trim(strtolower($status));
        if($status == 'invisible') {
            $status = $this->STATUS_ONLINE | $this->STATUS_FLAG_INVISIBLE;
            $status2 = 'STATUS_INVISIBLE';
        } elseif($status == 'online') {
            $status = $this->STATUS_ONLINE;
            $status2 = 'STATUS_ONLINE';
        } elseif($status == 'away') {
            $status = $this->STATUS_AWAY;
            $status2 = 'STATUS_AWAY';
        } else {
            $status2 = $status;
            $status = $this->STATUS_OTHER;
        }
        $xstatus = iconv('UTF-8','UTF-16LE//IGNORE',$xstatus);
        $xstatus_text = iconv('UTF-8','UTF-16LE//IGNORE',$xstatus_text);
        $data = pack('V2',$status,strlen($status2)).$status2.pack('V1',strlen($xstatus)).$xstatus.pack('V1',strlen($xstatus_text)).$xstatus_text.pack('V1',0x03FF);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_CHANGE_STATUS,$data));
        return true;
    }
    /**
     * mrim::add_contact()
     * 
     * Tries to add contact to server's contact list
     * 
     * @access public
     * @param string $addr
     * @param string $name
     * @param string $text
     * @param string $phone
     * @param int $group
     * @param bool $invisible
     * @param bool $visible
     * @param bool $ignore
     * @param bool $shadow
     * @param bool $deleted
     * @param bool $sms
     * @return bool
     */
    function add_contact($addr,$name,$text = 'Здравствуйте. Пожалуйста, добавьте меня в список ваших контактов.',$phone = '',$group = 0,$invisible = false,$visible = false,$ignore = false,$shadow = false,$deleted = false,$sms = false)
    {
        if(!$this->is_connected())
            return false;
        $flags = 0;
        if($invisible)
            $flags |= $this->CONTACT_FLAG_INVISIBLE;
        if($visible)
            $flags |= $this->CONTACT_FLAG_VISIBLE;
        if($ignore)
            $flags |= $this->CONTACT_FLAG_IGNORE;
        if($shadow)
            $flags |= $this->CONTACT_FLAG_SHADOW;
        if($deleted)
            $flags |= $this->CONTACT_FLAG_REMOVED;
        if($sms)
            $flags |= $this->CONTACT_FLAG_SMS;
        if($sms) {
            $group = 0x67;
            $addr = 'phone';
        }
        $nickname = ($this->nickname?$this->nickname:$this->email);
        $name = iconv('UTF-8','UTF-16LE//IGNORE',$name);
        $nickname = iconv('UTF-8','UTF-16LE//IGNORE',$nickname);
        $text = iconv('UTF-8','UTF-16LE//IGNORE',$text);
        $text = base64_encode(pack('V2',2,strlen($nickname)).$nickname.pack('V1',strlen($text)).$text);
        $data = pack('V3',$flags,$group,strlen($addr)).$addr.pack('V1',strlen($name)).$name.pack('V1',strlen($phone)).$phone.pack('V1',strlen($text)).$text.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_ADD_CONTACT,$data));
        return true;
    }
    /**
     * mrim::add_group()
     * 
     * Tries to add group to server's contact list
     * 
     * @access public
     * @param string $name
     * @return bool
     */
    function add_group($name)
    {
        if(!$this->is_connected())
            return false;
        $flags = $this->CONTACT_FLAG_GROUP;
        $nickname = ($this->nickname?$this->nickname:$this->email);
        $name = iconv('UTF-8','UTF-16LE//IGNORE',$name);
        $nickname = iconv('UTF-8','UTF-16LE//IGNORE',$nickname);
        $text = base64_encode(pack('V2',2,strlen($nickname)).$nickname.pack('V1',0));
        $data = pack('V4',$flags,0,0,strlen($name)).$name.pack('V2',0,strlen($text)).$text.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_ADD_CONTACT,$data));
        return true;
    }
    /**
     * mrim::modify_group()
     * 
     * Tries to modify (rename/delete) group in server's contact list
     * 
     * @access public
     * @param int $id
     * @param string $name
     * @param bool $shadow
     * @param bool $deleted
     * @return bool
     */
    function modify_group($id,$name,$shadow = false,$deleted = false)
    {
        if(!$this->is_connected())
            return false;
        $flags = $this->CONTACT_FLAG_GROUP;
        if($deleted)
            $flags |= $this->CONTACT_FLAG_REMOVED;
        if($shadow)
            $flags |= $this->CONTACT_FLAG_SHADOW;
        $name = iconv('UTF-8','UTF-16LE//IGNORE',$name);
        $data = pack('V5',$id,$flags,0,0,strlen($name)).$name.pack('V1',0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MODIFY_CONTACT,$data));
        return true;
    }
    /**
     * mrim::modify_contact()
     * 
     * Tries to modify contact in server's contact list
     * 
     * @access public
     * @param int $id
     * @param string $addr
     * @param string $name
     * @param int $group
     * @param string $phone
     * @param bool $invisible
     * @param bool $visible
     * @param bool $ignore
     * @param bool $shadow
     * @param bool $deleted
     * @param bool $sms
     * @return bool
     */
    function modify_contact($id,$addr,$name,$group,$phone = '',$invisible = false,$visible = false,$ignore = false,$shadow = false,$deleted = false,$sms = false)
    {
        if(!$this->is_connected())
            return false;
        $flags = 0;
        if($invisible)
            $flags |= $this->CONTACT_FLAG_INVISIBLE;
        if($visible)
            $flags |= $this->CONTACT_FLAG_VISIBLE;
        if($ignore)
            $flags |= $this->CONTACT_FLAG_IGNORE;
        if($shadow)
            $flags |= $this->CONTACT_FLAG_SHADOW;
        if($deleted)
            $flags |= $this->CONTACT_FLAG_REMOVED;
        if($sms)
            $flags |= $this->CONTACT_FLAG_SMS;
        if($sms) {
            $group = 0x67;
            $addr = 'phone';
        }
        $name = iconv('UTF-8','UTF-16LE//IGNORE',$name);
        $data = pack('V4',$id,$flags,$group,strlen($addr)).$addr.pack('V1',strlen($name)).$name.pack('V1',strlen($phone)).$phone;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MODIFY_CONTACT,$data));
        return true;
    }
    /**
     * mrim::find_contact()
     * 
     * Search contact in MRIM database
     * 
     * @access public
     * @param string $addr
     * @param string $nickname
     * @param string $name
     * @param string $lastname
     * @param mixed $sex
     * @param string $min
     * @param string $max
     * @param string $city
     * @param string $zodiac
     * @param string $month
     * @param string $day
     * @param string $country
     * @param bool $online
     * @return bool
     */
    function find_contact($addr = '',$nickname = '',$name = '',$lastname = '',$sex = false,$min = '',$max = '',$city = '',$zodiac = '',$month = '',$day = '',$country = '',$online = false)
    {
        if(!$this->is_connected())
            return false;
        $data = '';
        if($addr) {
            list($n,$d) = explode('@',$addr);
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_USER,strlen($n)).$n;
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_DOMAIN,strlen($d)).$d;
        }
        if($nickname) {
            $nickname = iconv('UTF-8','UTF-16LE//IGNORE',$nickname);
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_NICKNAME,strlen($nickname)).$nickname;
        }
        if($name) {
            $name = iconv('UTF-8','UTF-16LE//IGNORE',$name);
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_FIRSTNAME,strlen($name)).$name;
        }
        if($lastname) {
            $lastname = iconv('UTF-8','UTF-16LE//IGNORE',$lastname);
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_LASTNAME,strlen($lastname)).$lastname;
        }
        if(!is_bool($sex))
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_SEX,1).($sex == 2?2:1);
        if($min)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_DATE1,strlen($min)).$min;
        if($max)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_DATE2,strlen($max)).$max;
        if(strlen($city))
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_CITY_ID,strlen($city)).$city;
        if($zodiac)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_ZODIAC,strlen($zodiac)).$zodiac;
        if($month)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_BIRTHDAY_MONTH,strlen($month)).$month;
        if($day)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_BIRTHDAY_DAY,strlen($day)).$day;
        if(strlen($country))
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_COUNTRY_ID,strlen($country)).$country;
        if($online)
            $data .= pack('V2',$this->MRIM_CS_WP_REQUEST_PARAM_ONLINE,1).'1';
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_WP_REQUEST,$data));
        return true;
    }
    /**
     * mrim::post_microblog()
     * 
     * Posts message to mail.ru's microblog
     * 
     * @access public
     * @param string $message
     * @return bool
     */
    function post_microblog($message)
    {
        if(!$this->is_connected())
            return false;
        $message = iconv('UTF-8','UTF-16LE//IGNORE',$message);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_MICROBLOG_POST,pack('V2',0x09,strlen($message)).$message));
        return true;
    }
    /**
     * mrim::receive_files()
     * 
     * Accepts or declines incoming filetransfer
     * 
     * @access public
     * @param array $uinfo
     * @param bool $accept
     * @param string $put
     * @return bool
     */
    function receive_files($uinfo,$accept = true,$put = '.')
    {
        if(!$this->is_connected())
            return false;
        if(!is_array($uinfo))
            return false;
        if($this->behaviour != 1) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_INCOMPATIBLE_VERS,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        if(!$accept) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_DECLINE,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return true;
        }
        if(!is_dir($put) || !is_writable($put)) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        for($i = 0; $i < count($uinfo['ips']); $i++) {
            $conn = false;
            if($this->proxy_type) {
                $sock = fsockopen($this->proxy,$this->proxy_port,$en,$es,$this->timeout);
                if($sock)
                    if($this->proxy_type == 'http') {
                        if($this->proxy_user && $this->proxy_pass)
                            $au = "Proxy-Authorization: basic ".base64_encode($this->proxy_user.":".$this->proxy_pass)."\r\n";
                        else
                            $au = '';
                        fputs($sock,"CONNECT ".$uinfo['ips'][$i]['ip'].":".$uinfo['ips'][$i]['port']." HTTP/1.0\r\nHost: ".$uinfo['ips'][$i]['ip'].":".$uinfo['ips'][$i]['port']."\r\nUser-Agent: ".$this->user_agent."\r\n".$au."\r\n");
                        $code = intval(substr(trim(fgets($sock,1024)),9,3));
                        if($code == 200) {
                            while(($a = trim(fgets($sock,1024)) != ''))
                                ;
                            $conn = $sock;
                        } else
                            fclose($sock);
                    } elseif($this->proxy_type == 'socks4') {
                        $ip = gethostbyname($uinfo['ips'][$i]['ip']);
                        if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches)) {
                            $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                            $request = pack('C2',0x04,0x01).pack('n',$uinfo['ips'][$i]['port']).$int.($this->proxy_user)?$this->proxy_user:'0'.pack('C',0x00);
                            fwrite($sock,$request);
                            $resp = fread($sock,9);
                            $answer = unpack('Cvn/Ccd',substr($resp,0,2));
                            if($answer['vn'] == 0x00 && $answer['cd'] == 0x5A)
                                $conn = $sock;
                            else
                                fclose($sock);
                        } else
                            fclose($sock);
                    } elseif($this->proxy_type == 'socks5') {
                        $flag = false;
                        if($this->proxy_user && $this->proxy_pass)
                            $request = pack('C4',0x05,0x02,0x00,0x02);
                        else
                            $request = pack('C3',0x05,0x01,0x00);
                        fwrite($sock,$request);
                        $resp = fread($sock,3);
                        $answer = unpack('Cver/Cmethod',$resp);
                        if($answer['method'] == 0x02) {
                            $request = pack('C',0x01).pack('C',strlen($this->proxy_user)).$this->proxy_user.pack('C',strlen($this->proxy_pass)).$this->proxy_pass;
                            fwrite($sock,$request);
                            $resp = fread($sock,3);
                            $answer = unpack('Cvn/CresuVt',$resp);
                            if($answer['vn'] != 0x01 && $answer['result'] != 0x00) {
                                fclose($sock);
                                $flag = true;
                            } else
                                $answer['method'] = 0;
                        }
                        if($answer['method'] == 0x00) {
                            $ip = gethostbyname($uinfo['ips'][$i]['ip']);
                            if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches)) {
                                $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                                $request = pack('C4',0x05,0x01,0x00,0x01).$int.pack('n',$uinfo['ips'][$i]['port']);
                                fwrite($fp,$request);
                                $resp = fread($fp,11);
                                $answer = unpack('Cver/CREP',substr($resp,0,2));
                                if($answer['REP'] == 0x00)
                                    $conn = $sock;
                                else
                                    fclose($sock);
                            } else
                                fclose($sock);
                        } else
                            fclose($sock);
                    }
            } else
                $conn = fsockopen($uinfo['ips'][$i]['ip'],$uinfo['ips'][$i]['port'],$en,$es,$this->timeout);
            if($conn)
                break;
        }
        if(!function_exists('stream_socket_get_name') || !function_exists('stream_socket_accept')) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        $passive = true;
        if(!$conn) {
            $passive = false;
            if(!$this->cn1 && !$this->cn2) {
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
            $ipdata = stream_socket_get_name($this->sock,false);
            list($ip,$port) = explode(':',$ipdata);
            if(!$ip)
                $ip = $this->ip;
            $ipdata = '';
            if($this->cn1)
                $ipdata .= $ip.':2041;';
            if($this->cn2)
                $ipdata .= $ip.':443;';
            $data = pack('V2',$this->FILE_TRANSFER_MIRROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],strlen($ipdata)).$ipdata;
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            if($this->cn1)
                $conn = stream_socket_accept($this->cn1,$this->timeout);
            if($this->cn2 && !$conn)
                $conn = stream_socket_accept($this->cn2,$this->timeout);
        }
        if(!$conn) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        if($passive) {
            fwrite($conn,"MRA_FT_HELLO ".$this->email."\0");
            $resp = fread($conn,512);
            if($resp != "MRA_FT_HELLO ".$uinfo['email']."\0") {
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
        } else {
            $resp = fread($conn,512);
            if($resp != "MRA_FT_HELLO ".$uinfo['email']."\0") {
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
            fwrite($conn,"MRA_FT_HELLO ".$this->email."\0");
        }
        if(!is_resource($conn) || feof($conn)) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        for($i = 0; $i < count($uinfo['name']); $i++) {
            if(is_resource($conn) && !feof($conn)) {
                $fp = fopen($put.'/'.basename($uinfo['name'][$i]['name']),'w+');
                if($fp) {
                    fwrite($conn,"MRA_FT_GET_FILE ".$uinfo['name'][$i]['name']."\0");
                    for($j = 0; $j < $uinfo['name'][$i]['size']; $j++)
                        fwrite($fp,fgetc($conn));
                    fclose($fp);
                }
            }
        }
        fclose($conn);
        $data = pack('V2',$this->FILE_TRANSFER_STATUS_OK,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
        return true;
    }
    /**
     * mrim::send_files()
     * 
     * Sends filetransfer request
     * 
     * @access public
     * @param string $to
     * @param array $files
     * @return mixed
     */
    function send_files($to,$files)
    {
        if(!$this->is_connected())
            return false;
        if($this->behaviour != 1)
            return false;
        if(!$this->cn1 && !$this->cn2)
            return false;
        if(!function_exists('stream_socket_get_name'))
            return false;
        $ret['id'] = rand(0x000000,0xFFFFFF);
        $ret['files'] = array();
        $size = 0;
        for($i = 0; $i < count($files); $i++)
            if(is_file($files[$i]) && is_readable($files[$i])) {
                $ret['files'][] = array('path' => realpath($files[$i]),'name' => basename($files[$i]),'uname' => iconv('CP1251','UTF-8//IGNORE',basename($files[$i])),'size' => filesize($files[$i]));
                $size += filesize($files[$i]);
            }
        if(count($ret['files']) == 0)
            return false;
        $name = '';
        for($i = 0; $i < count($ret['files']); $i++)
            $name .= $ret['files'][$i]['name'].';'.$ret['files'][$i]['size'].';';
        $uname = iconv('CP1251','UTF-16LE//IGNORE',$name);
        $ipdata = stream_socket_get_name($this->sock,false);
        list($ip,$port) = explode(':',$ipdata);
        $ipdata = '';
        if(!$ip)
            $ip = $this->ip;
        if($this->cn1)
            $ipdata .= $ip.':2041;';
        if($this->cn2)
            $ipdata .= $ip.':443;';
        $data = pack('V2',0x01,strlen($uname)).$uname;
        $data = pack('V1',strlen($name)).$name.pack('V1',strlen($data)).$data.pack('V1',strlen($ipdata)).$ipdata;
        $data = pack('V1',strlen($to)).$to.pack('V3',$ret['id'],$size,strlen($data)).$data;
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER,$data));
        if($this->cn1)
            $conn = stream_socket_accept($this->cn1,60);
        if($this->cn2 && !$conn)
            $conn = stream_socket_accept($this->cn2,60);
        if($conn) {
            $resp = fread($conn,512);
            if($resp != "MRA_FT_HELLO ".$to."\0") {
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($to)).$to.pack('V2',$ret['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
            fwrite($conn,"MRA_FT_HELLO ".$this->email."\0");
            for($j = 0; $j < count($ret['files']); $j++) {
                $resp = fread($conn,1024);
                list($cmd,$fn) = explode(' ',$resp);
                if($cmd != 'MRA_FT_GET_FILE') {
                    fclose($conn);
                    $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($to)).$to.pack('V2',$ret['id'],0);
                    fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                    return false;
                }
                $fn = trim($fn);
                $flag = false;
                for($i = 0; $i < count($ret['files']); $i++)
                    if($ret['files'][$i]['name'] == $fn) {
                        $flag = true;
                        $fp = fopen($ret['files'][$i]['path'],'r');
                        while(!feof($fp))
                            fwrite($conn,fread($fp,256));
                        fclose($fp);
                        break;
                    }
                if(!$flag) {
                    fclose($conn);
                    $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($to)).$to.pack('V2',$ret['id'],0);
                    fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                    return false;
                }
            }
            fclose($conn);
        } else
            $ret['error'] = true;
        return $ret;
    }
    /**
     * mrim::mirror_filetransfer()
     * 
     * Starts mirrored outgoing filetransfer
     * 
     * @access public
     * @param array $result
     * @param array $uinfo
     * @return mixed
     */
    function mirror_filetransfer($result,$uinfo)
    {
        if(!$this->is_connected())
            return false;
        if($this->behaviour != 1)
            return false;
        if(!is_array($result) || !is_array($uinfo))
            return false;
        if(!$uinfo['ips'] || !$result['error'])
            return false;
        if($uinfo['id'] != $result['id'])
            return false;
        for($i = 0; $i < count($uinfo['ips']); $i++) {
            $conn = false;
            if($this->proxy_type) {
                $sock = fsockopen($this->proxy,$this->proxy_port,$en,$es,$this->timeout);
                if($sock)
                    if($this->proxy_type == 'http') {
                        if($this->proxy_user && $this->proxy_pass)
                            $au = "Proxy-Authorization: basic ".base64_encode($this->proxy_user.":".$this->proxy_pass)."\r\n";
                        else
                            $au = '';
                        fputs($sock,"CONNECT ".$uinfo['ips'][$i]['ip'].":".$uinfo['ips'][$i]['port']." HTTP/1.0\r\nHost: ".$uinfo['ips'][$i]['ip'].":".$uinfo['ips'][$i]['port']."\r\nUser-Agent: ".$this->user_agent."\r\n".$au."\r\n");
                        $code = intval(substr(trim(fgets($sock,1024)),9,3));
                        if($code == 200) {
                            while(($a = trim(fgets($sock,1024)) != ''))
                                ;
                            $conn = $sock;
                        } else
                            fclose($sock);
                    } elseif($this->proxy_type == 'socks4') {
                        $ip = gethostbyname($uinfo['ips'][$i]['ip']);
                        if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches)) {
                            $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                            $request = pack('C2',0x04,0x01).pack('n',$uinfo['ips'][$i]['port']).$int.($this->proxy_user)?$this->proxy_user:'0'.pack('C',0x00);
                            fwrite($sock,$request);
                            $resp = fread($sock,9);
                            $answer = unpack('Cvn/Ccd',substr($resp,0,2));
                            if($answer['vn'] == 0x00 && $answer['cd'] == 0x5A)
                                $conn = $sock;
                            else
                                fclose($sock);
                        } else
                            fclose($sock);
                    } elseif($this->proxy_type == 'socks5') {
                        $flag = false;
                        if($this->proxy_user && $this->proxy_pass)
                            $request = pack('C4',0x05,0x02,0x00,0x02);
                        else
                            $request = pack('C3',0x05,0x01,0x00);
                        fwrite($sock,$request);
                        $resp = fread($sock,3);
                        $answer = unpack('Cver/Cmethod',$resp);
                        if($answer['method'] == 0x02) {
                            $request = pack('C',0x01).pack('C',strlen($this->proxy_user)).$this->proxy_user.pack('C',strlen($this->proxy_pass)).$this->proxy_pass;
                            fwrite($sock,$request);
                            $resp = fread($sock,3);
                            $answer = unpack('Cvn/Cresult',$resp);
                            if($answer['vn'] != 0x01 && $answer['result'] != 0x00) {
                                fclose($sock);
                                $flag = true;
                            } else
                                $answer['method'] = 0;
                        }
                        if($answer['method'] == 0x00) {
                            $ip = gethostbyname($uinfo['ips'][$i]['ip']);
                            if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches)) {
                                $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                                $request = pack('C4',0x05,0x01,0x00,0x01).$int.pack('n',$uinfo['ips'][$i]['port']);
                                fwrite($fp,$request);
                                $resp = fread($fp,11);
                                $answer = unpack('Cver/CREP',substr($resp,0,2));
                                if($answer['REP'] == 0x00)
                                    $conn = $sock;
                                else
                                    fclose($sock);
                            } else
                                fclose($sock);
                        } else
                            fclose($sock);
                    }
            } else
                $conn = fsockopen($uinfo['ips'][$i]['ip'],$uinfo['ips'][$i]['port'],$en,$es,$this->timeout);
            if($conn)
                break;
        }
        if(!$conn) {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        fwrite($conn,"MRA_FT_HELLO ".$this->email."\0");
        $resp = fread($conn,512);
        if($resp != "MRA_FT_HELLO ".$uinfo['email']."\0") {
            $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
            return false;
        }
        for($j = 0; $j < count($result['files']); $j++) {
            $resp = fread($conn,1024);
            list($cmd,$fn) = explode(' ',$resp);
            if($cmd != 'MRA_FT_GET_FILE') {
                fclose($conn);
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
            $fn = trim($fn);
            $flag = false;
            for($i = 0; $i < count($result['files']); $i++)
                if($result['files'][$i]['name'] == $fn) {
                    $flag = true;
                    $fp = fopen($result['files'][$i]['path'],'r');
                    while(!feof($fp))
                        fwrite($conn,fread($fp,256));
                    fclose($fp);
                    break;
                }
            if(!$flag) {
                fclose($conn);
                $data = pack('V2',$this->FILE_TRANSFER_STATUS_ERROR,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
                fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
                return false;
            }
        }
        fclose($conn);
        $data = pack('V2',$this->FILE_TRANSFER_STATUS_OK,strlen($uinfo['email'])).$uinfo['email'].pack('V2',$uinfo['id'],0);
        fwrite($this->sock,$this->make_packet($this->MRIM_CS_FILE_TRANSFER_ACK,$data));
        return true;
    }
    /**
     * mrim::receive_packet()
     * 
     * Receives one packet from server
     * 
     * @access public
     * @return mixed
     */
    function receive_packet()
    {
        if(!$this->is_connected())
            return false;
        if($this->behaviour == 1) {
            stream_set_timeout($this->sock,1);
            $answ = fread($this->sock,44);
            if(!strlen($answ))
                return false;
            while(strlen($answ) < 44) {
                $answ .= fread($this->sock,44 - strlen($answ));
                if(!$this->is_connected())
                    return false;
            }
            list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4) = unpack('V*',$answ);
            if($dlen == 0)
                return array($msg,'');
            $a = fread($this->sock,$dlen);
            while(strlen($a) < $dlen) {
                $a .= fread($this->sock,$dlen - strlen($a));
                if(!$this->is_connected())
                    return false;
            }
        } else {
            $answ = fread($this->sock,1);
            if(strlen($answ) == 1) {
                stream_set_blocking($this->sock,1);
                while(strlen($answ) < 44) {
                    $answ .= fread($this->sock,44 - strlen($answ));
                    if(!$this->is_connected())
                        return false;
                }
            }
            if(!$answ)
                return false;
            list(,$magic,$proto,$seq,$msg,$dlen,$from,$fromport,$r1,$r2,$r3,$r4) = unpack('V*',$answ);
            if($dlen == 0)
                return array($msg,'');
            $a = fread($this->sock,$dlen);
            while(strlen($a) < $dlen) {
                $a .= fread($this->sock,$dlen - strlen($a));
                if(!$this->is_connected())
                    return false;
            }
            stream_set_blocking($this->sock,0);
        }
        return array($msg,$a,$seq);
    }
    /**
     * mrim::is_filetransfer_status()
     * 
     * Checks if incoming packet is filetransfer status
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_filetransfer_status($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_FILE_TRANSFER_ACK)
            return false;
        list(,$status,$len) = unpack('V2',$packet[1]);
        if($status = $this->FILE_TRANSFER_STATUS_DECLINE)
            $ret['status'] = 'declined';
        elseif($status = $this->FILE_TRANSFER_STATUS_ERROR)
            $ret['status'] = 'error';
        elseif($status = $this->FILE_TRANSFER_MIRROR)
            $ret['status'] = 'mirror';
        elseif($status = $this->FILE_TRANSFER_STATUS_INCOMPATIBLE_VERS)
            $ret['status'] = 'incompatible';
        elseif($status = $this->FILE_TRANSFER_STATUS_OK)
            $ret['status'] = 'ok';
        $packet[1] = substr($packet[1],8);
        $ret['email'] = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$id,$len) = unpack('V2',$packet[1]);
        $ret['id'] = $id;
        $packet[1] = substr($packet[1],8);
        if($len) {
            $lps1 = substr($packet[1],0,$len);
            $lps1 = substr($lps1,0,-1);
            $ips = explode(';',$lps1);
            $ret['ips'] = array();
            for($i = 0; $i < count($ips); $i++) {
                list($ip,$port) = explode(':',$ips[$i]);
                $ret['ips'][] = array('ip' => $ip,'port' => intval($port));
            }
        }
        return $ret;
    }
    /**
     * mrim::is_filetransfer()
     * 
     * Checks if incoming packet is filetransfer request
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_filetransfer($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_FILE_TRANSFER)
            return false;
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $ret['email'] = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$id,$size,$len) = unpack('V3',$packet[1]);
        $ret['id'] = $id;
        $ret['size'] = $size;
        $packet[1] = substr($packet[1],12);
        $packet[1] = substr($packet[1],0,$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $lps1 = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        $packet[1] = substr($packet[1],8);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $lps2 = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $lps3 = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        $lps1 = substr($lps1,0,-1);
        $lps2 = substr($lps2,0,-1);
        $lps3 = substr($lps3,0,-1);
        $name = explode(';',$lps1);
        $ret['name'] = array();
        for($i = 0; $i < count($name); $i++)
            $ret['name'][] = array('name' => $name[$i],'size' => intval($name[++$i]));
        $uname = explode(';',$lps2);
        $ret['uname'] = array();
        for($i = 0; $i < count($uname); $i++)
            $ret['uname'][] = array('name' => $uname[$i],'size' => intval($uname[++$i]));
        $ips = explode(';',$lps3);
        $ret['ips'] = array();
        for($i = 0; $i < count($ips); $i++) {
            list($ip,$port) = explode(':',$ips[$i]);
            $ret['ips'][] = array('ip' => $ip,'port' => intval($port));
        }
        return $ret;
    }
    /**
     * mrim::is_user_info()
     * 
     * Checks if incoming packet is user information
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_user_info($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_USER_INFO)
            return false;
        $p = $packet[1];
        $ret = array();
        $cnt = 0;
        while(true) {
            list(,$len) = unpack('V1',$p);
            $p = substr($p,4);
            $ret[$cnt]['param'] = substr($p,0,$len);
            $p = substr($p,$len);
            list(,$len) = unpack('V1',$p);
            $p = substr($p,4);
            $ret[$cnt]['value'] = iconv('UTF-16LE','UTF-8//IGNORE',substr($p,0,$len));
            $p = substr($p,$len);
            $cnt++;
            if(strlen($p) == 0)
                break;
        }
        for($i = 0; $i < count($ret); $i++) {
            if($ret[$i]['param'] == 'MRIM.NICKNAME')
                $this->nickname = $ret[$i]['value'];
            if($ret[$i]['param'] == 'client.endpoint') {
                list($ip) = explode(':',$ret[$i]['value']);
                $this->ip = trim($ip);
            }
        }
        return $ret;
    }
    /**
     * mrim::is_user_status()
     * 
     * Checks if incoming packet is user status
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_user_status($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_USER_STATUS)
            return false;
        list(,$status,$len) = unpack('V2',$packet[1]);
        $packet[1] = substr($packet[1],8);
        $status2 = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $xstatus = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $xstatus_text = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $email = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$unk) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $client = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        $status = intval($status);
        if($status == $this->STATUS_OFFLINE)
            $status = 'offline';
        elseif($status == $this->STATUS_ONLINE)
            $status = 'online';
        elseif($status == $this->STATUS_AWAY)
            $status = 'away';
        elseif($status & $this->STATUS_FLAG_INVISIBLE)
            $status = 'invisible';
        elseif($status == $this->STATUS_OTHER)
            $status = 'other';
        elseif($status == $this->STATUS_UNDETERMINATED)
            $status = 'undeterminated';
        else
            $status = 'unknown';
        return array('status' => $status,'status2' => $status2,'xstatus' => $xstatus,'xstatus_text' => $xstatus_text,'email' => $email,'client' => $client,'unk' => $unk);
    }
    /**
     * mrim::is_session_key()
     * 
     * Checks if incoming packet is session key
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_session_key($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MPOP_SESSION)
            return false;
        list(,$success,$len) = unpack('V2',$packet[1]);
        if($success == $this->MRIM_GET_SESSION_SUCCESS)
            return 'http://win.mail.ru/cgi-bin/auth?Login='.$this->email.'&agent='.substr(substr($packet[1],8),0,$len);
        return false;
    }
    /**
     * mrim::is_mailbox_status()
     * 
     * Checks if incoming packet is mailbox status
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_mailbox_status($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MAILBOX_STATUS)
            return false;
        list(,$c) = unpack('V1',$packet[1]);
        return $c;
    }
    /**
     * mrim::is_new_email()
     * 
     * Checks if incoming packet is new e-mail notification
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_new_email($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_NEW_EMAIL)
            return false;
        list(,$status,$len) = unpack('V2',$packet[1]);
        if(!$len)
            return false;
        $packet[1] = substr($packet[1],8);
        $ret['from'] = iconv('CP1251','UTF-8//IGNORE',substr($packet[1],0,$len));
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $ret['subject'] = iconv('CP1251','UTF-8//IGNORE',substr(substr($packet[1],4),0,$len));
        $ret['unread'] = $status;
        return $ret;
    }
    /**
     * mrim::is_message()
     * 
     * Checks if incoming packet is MRIM message
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_message($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MESSAGE_ACK)
            return false;
        list(,$seq,$flag,$len) = unpack('V3',$packet[1]);
        $packet[1] = substr($packet[1],12);
        $ret['from'] = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $text = substr($packet[1],0,$len);
        if($this->status != 'invisible' && !($flag & $this->MESSAGE_FLAG_NORECV)) {
            $data = pack('V1',strlen($ret['from'])).$ret['from'].pack('V1',$seq);
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_MESSAGE_RECV,$data));
        }
        if($flag & $this->MESSAGE_FLAG_ALARM)
            $ret['type'] = 'alarm';
        elseif($flag & $this->MESSAGE_FLAG_AUTHORIZE)
            $ret['type'] = 'auth';
        elseif($flag & $this->MESSAGE_FLAG_CONTACT)
            $ret['type'] = 'contact';
        elseif($flag & $this->MESSAGE_FLAG_MULTICAST)
            $ret['type'] = 'multi';
        elseif($flag & $this->MESSAGE_FLAG_NOTIFY)
            $ret['type'] = 'typing';
        elseif($flag & $this->MESSAGE_FLAG_SYSTEM)
            $ret['type'] = 'system';
        else
            $ret['type'] = 'message';
        if($flag & $this->MESSAGE_FLAG_SPAM)
            $ret['spam'] = true;
        if($flag & $this->MESSAGE_FLAG_SMS_NOTIFY || $flag & $this->MESSAGE_FLAG_SMS)
            $ret['type'] = 'sms';
        $nickname = false;
        if($ret['type'] == 'auth') {
            $text = base64_decode($text);
            $text = substr($text,4);
            list(,$len) = unpack('V1',$text);
            $text = substr($text,4);
            $ret['nickname'] = iconv('UTF-16LE','UTF-8//IGNORE',substr($text,0,$len));
            $text = substr($text,$len);
            list(,$len) = unpack('V1',$text);
            $text = substr($text,4);
            $text = substr($text,0,$len);
        }
        if($flag & $this->MESSAGE_FLAG_OLD)
            $text = iconv('CP1251','UTF-8//IGNORE',$text);
        else
            $text = iconv('UTF-16LE','UTF-8//IGNORE',$text);
        $ret['text'] = $text;
        return $ret;
    }
    /**
     * mrim::is_message_status()
     * 
     * Checks if incoming packet is MRIM message delivery status
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_message_status($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MESSAGE_STATUS)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        $ret['id'] = $packet[2];
        if($status == $this->MESSAGE_DELIVERED)
            $ret['status'] = 'ok';
        elseif($status == $this->MESSAGE_REJECTED_DENY_OFFMSG)
            $ret['status'] = 'blocked offline message';
        elseif($status == $this->MESSAGE_REJECTED_INTERR)
            $ret['status'] = 'internal error';
        elseif($status == $this->MESSAGE_REJECTED_LIMIT_EXCEEDED)
            $ret['status'] = 'limit exceeded';
        elseif($status == $this->MESSAGE_REJECTED_NOUSER)
            $ret['status'] = 'no user';
        elseif($status == $this->MESSAGE_REJECTED_TOO_LARGE)
            $ret['status'] = 'too large';
        else
            $ret['status'] = 'unknown message status';
        return $ret;
    }
    /**
     * mrim::is_new_ping()
     * 
     * Checks if incoming packet is new ping timeout notification
     * 
     * @access public
     * @param array $packet
     * @return bool
     */
    function is_new_ping($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_CONNECTION_PARAMS)
            return false;
        list(,$newping) = unpack('V1',$packet[1]);
        $this->ping_time = $newping;
        return true;
    }
    /**
     * mrim::is_logout()
     * 
     * Checks if incoming packet is logout notification
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_logout($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_LOGOUT)
            return false;
        list(,$reason) = unpack('V1',$packet[1]);
        if($reason & $this->LOGOUT_NO_RELOGIN_FLAG)
            return array('no-relogin' => true);
        return array('no-relogin' => false);
    }
    /**
     * mrim::is_authorize()
     * 
     * Checks if incoming packet is authorize notification
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_authorize($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_AUTHORIZE_ACK)
            return false;
        list(,$len) = unpack('V1',$packet[1]);
        if(!$len)
            return false;
        return substr(substr($packet[1],4),0,$len);
    }
    /**
     * mrim::is_offline_message()
     * 
     * Checks if incoming packet is offline MRIM message
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_offline_message($packet,$delete = true)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_OFFLINE_MESSAGE_ACK)
            return false;
        $id = substr($packet[1],0,8);
        $packet[1] = substr($packet[1],8);
        list(,$len) = unpack('V1',$packet[1]);
        if($len == 0)
            return false;
        $packet[1] = substr($packet[1],4);
        $message = trim(substr($packet[1],0,$len));
        if($delete)
            fwrite($this->sock,$this->make_packet($this->MRIM_CS_DELETE_OFFLINE_MESSAGE,$id));
        $message = $this->crlf_validate($message);
        $p = $this->separate_headers($message);
        $headers = $this->decode_header($p['headers']);
        $message = $p['body'];
        $arr = explode(';',$headers['content-type']);
        $ctype = trim(strtolower($arr[0]));
        $flag = hexdec(trim($headers['x-mrim-flags']));
        $ret['from'] = trim(strtolower($headers['from']));
        $ret['date'] = strtotime(trim($headers['date']));
        if($flag & $this->MESSAGE_FLAG_SPAM)
            $ret['spam'] = true;
        list($a,$b) = explode('/',$ctype);
        if($a == 'multipart') {
            $boundary = $this->get_boundary($headers);
            $parts = $this->split_parts($boundary,$message);
            for($i = 0; $i < count($parts); $i++) {
                $message = $parts[$i];
                $p = $this->separate_headers($message);
                $headers = $this->decode_header($p['headers']);
                $message = $p['body'];
                $arr = explode(';',$headers['content-type']);
                $ctype = trim(strtolower($arr[0]));
                list($a,$b) = explode('/',$ctype);
                $charset = strtoupper(trim($this->get_charset($headers)));
                $charset = str_replace('CP-','CP',$charset);
                if(trim(strtolower($headers['content-transfer-encoding'])) == 'base64')
                    $message = base64_decode($message);
                if(trim(strtolower($headers['content-transfer-encoding'])) == 'quoted-printable')
                    $message = $this->decode_qp($message);
                if(($a == 'application' && $b == 'x-mrim-auth-req') || $flag & $this->MESSAGE_FLAG_AUTHORIZE) {
                    $ret['type'] = 'auth';
                    $message = base64_decode($message);
                    $message = substr($message,4);
                    list(,$len) = unpack('V1',$message);
                    $message = substr($message,4);
                    if($charset)
                        $ret['nickname'] = trim(iconv($charset,'UTF-8//IGNORE',substr($message,0,$len)));
                    elseif($flag & $this->MESSAGE_FLAG_OLD)
                        $ret['nickname'] = trim(iconv('CP1251','UTF-8//IGNORE',substr($message,0,$len)));
                    else
                        $ret['nickname'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',substr($message,0,$len)));
                    $message = substr($message,$len);
                    list(,$len) = unpack('V1',$message);
                    $message = substr($message,4);
                    $message = substr($message,0,$len);
                    if($charset)
                        $ret['text'] = trim(iconv($charset,'UTF-8//IGNORE',$message));
                    elseif($flag & $this->MESSAGE_FLAG_OLD)
                        $ret['text'] = trim(iconv('CP1251','UTF-8//IGNORE',$message));
                    else
                        $ret['text'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',$message));
                } elseif($a == 'text') {
                    if($flag & $this->MESSAGE_FLAG_CONTACT)
                        $ret['type'] = 'contact';
                    elseif($flag & $this->MESSAGE_FLAG_MULTICAST)
                        $ret['type'] = 'multi';
                    elseif($flag & $this->MESSAGE_FLAG_SMS_NOTIFY || $flag & $this->MESSAGE_FLAG_SMS)
                        $ret['type'] = 'sms';
                    else
                        $ret['type'] = 'message';
                    if($charset)
                        $ret['text'] = trim(iconv($charset,'UTF-8//IGNORE',$message));
                    elseif($flag & $this->MESSAGE_FLAG_OLD)
                        $ret['text'] = trim(iconv('CP1251','UTF-8//IGNORE',$message));
                    else
                        $ret['text'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',$message));
                }
            }
        } else {
            $charset = strtoupper(trim($this->get_charset($headers)));
            $charset = str_replace('CP-','CP',$charset);
            if(trim(strtolower($headers['content-transfer-encoding'])) == 'base64')
                $message = base64_decode($message);
            if(trim(strtolower($headers['content-transfer-encoding'])) == 'quoted-printable')
                $message = $this->decode_qp($message);
            if(($a == 'application' && $b == 'x-mrim-auth-req') || $flag & $this->MESSAGE_FLAG_AUTHORIZE) {
                $ret['type'] = 'auth';
                $message = base64_decode($message);
                $message = substr($message,4);
                list(,$len) = unpack('V1',$message);
                $message = substr($message,4);
                if($charset)
                    $ret['nickname'] = trim(iconv($charset,'UTF-8//IGNORE',substr($message,0,$len)));
                elseif($flag & $this->MESSAGE_FLAG_OLD)
                    $ret['nickname'] = trim(iconv('CP1251','UTF-8//IGNORE',substr($message,0,$len)));
                else
                    $ret['nickname'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',substr($message,0,$len)));
                $message = substr($message,$len);
                list(,$len) = unpack('V1',$message);
                $message = substr($message,4);
                $message = substr($message,0,$len);
                if($charset)
                    $ret['text'] = trim(iconv($charset,'UTF-8//IGNORE',$message));
                elseif($flag & $this->MESSAGE_FLAG_OLD)
                    $ret['text'] = trim(iconv('CP1251','UTF-8//IGNORE',$message));
                else
                    $ret['text'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',$message));
            } else {
                if($flag & $this->MESSAGE_FLAG_CONTACT)
                    $ret['type'] = 'contact';
                elseif($flag & $this->MESSAGE_FLAG_MULTICAST)
                    $ret['type'] = 'multi';
                elseif($flag & $this->MESSAGE_FLAG_SMS_NOTIFY || $flag & $this->MESSAGE_FLAG_SMS)
                    $ret['type'] = 'sms';
                else
                    $ret['type'] = 'message';
                if($charset)
                    $ret['text'] = trim(iconv($charset,'UTF-8//IGNORE',$message));
                elseif($flag & $this->MESSAGE_FLAG_OLD)
                    $ret['text'] = trim(iconv('CP1251','UTF-8//IGNORE',$message));
                else
                    $ret['text'] = trim(iconv('UTF-16LE','UTF-8//IGNORE',$message));
            }
        }
        return $ret;
    }
    /**
     * mrim::is_contact_list()
     * 
     * Checks if incoming packet is server's contact list
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_contact_list($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_CONTACT_LIST2)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        if($status == $this->GET_CONTACTS_INTERR)
            return 'internal error';
        if($status == $this->GET_CONTACTS_ERROR)
            return 'error';
        if($status != $this->GET_CONTACTS_OK)
            return false;
        list(,$groups) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $group_mask = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $contact_mask = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len);
        $this->groups = $groups;
        for($i = 0; $i < $groups; $i++) {
            if($group_mask[0] == 'u') {
                list(,$flag) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
            } else
                return false;
            if($group_mask[1] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $name = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            $ret['group'][$i]['name'] = $name;
            if($flag & $this->CONTACT_FLAG_REMOVED)
                $ret['group'][$i]['deleted'] = true;
            else
                $ret['group'][$i]['deleted'] = false;
            if($flag & $this->CONTACT_FLAG_SHADOW)
                $ret['group'][$i]['shadow'] = true;
            else
                $ret['group'][$i]['shadow'] = false;
            if(strlen($group_mask) > 2) {
                for($j = 2; $j < strlen($group_mask); $j++) {
                    if($group_mask[$j] == 'u')
                        $packet[1] = substr($packet[1],4);
                    elseif($group_mask[$j] == 's') {
                        list(,$len) = unpack('V1',$packet[1]);
                        $packet[1] = substr($packet[1],4);
                        $packet[1] = substr($packet[1],$len);
                    } else {
                        while(true) {
                            if(substr($packet[1],0,1) != "\0")
                                $packet[1] = substr($packet[1],1);
                            else {
                                $packet[1] = substr($packet[1],1);
                                break;
                            }
                        }
                    }
                }
            }
        }
        $i = 0;
        while(strlen($packet[1]) > 0) {
            if($contact_mask[0] == 'u') {
                list(,$flag) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
            } else
                return false;
            if($contact_mask[1] == 'u') {
                list(,$group) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
            } else
                return false;
            if($contact_mask[2] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $addr = substr($packet[1],0,$len);
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[3] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $name = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[4] == 'u') {
                list(,$sflags) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
            } else
                return false;
            if($contact_mask[5] == 'u') {
                list(,$status) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
            } else
                return false;
            if($contact_mask[6] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $phone = substr($packet[1],0,$len);
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[7] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $status2 = substr($packet[1],0,$len);
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[8] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $xstatus = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[9] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $xstatus_text = iconv('UTF-16LE','UTF-8//IGNORE',substr($packet[1],0,$len));
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if($contact_mask[10] == 'u')
                $packet[1] = substr($packet[1],4);
            else
                return false;
            if($contact_mask[11] == 's') {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $client = substr($packet[1],0,$len);
                $packet[1] = substr($packet[1],$len);
            } else
                return false;
            if(strlen($contact_mask) > 12) {
                for($j = 12; $j < strlen($contact_mask); $j++) {
                    if($contact_mask[$j] == 'u')
                        $packet[1] = substr($packet[1],4);
                    elseif($contact_mask[$j] == 's') {
                        list(,$len) = unpack('V1',$packet[1]);
                        $packet[1] = substr($packet[1],4);
                        $packet[1] = substr($packet[1],$len);
                    } else {
                        while(true) {
                            if(substr($packet[1],0,1) != "\0")
                                $packet[1] = substr($packet[1],1);
                            else {
                                $packet[1] = substr($packet[1],1);
                                break;
                            }
                        }
                    }
                }
            }
            if($status == $this->STATUS_OFFLINE)
                $status = 'offline';
            elseif($status == $this->STATUS_ONLINE)
                $status = 'online';
            elseif($status == $this->STATUS_AWAY)
                $status = 'away';
            elseif($status & $this->STATUS_FLAG_INVISIBLE)
                $status = 'invisible';
            elseif($status == $this->STATUS_OTHER)
                $status = 'other';
            elseif($status == $this->STATUS_UNDETERMINATED)
                $status = 'undeterminated';
            else
                $status = 'unknown';
            if(isset($ret['group'][$group]))
                $ret['group'][$group]['contacts'][] = array('addr' => $addr,'name' => $name,'status' => $status,'phone' => $phone,'not-authorized' => ($sflags & $this->CONTACT_INTFLAG_NOT_AUTHORIZED?true:false),'ignore' => ($flag & $this->CONTACT_FLAG_IGNORE?true:false),'invisible' => ($flag & $this->CONTACT_FLAG_INVISIBLE?true:false),'visible' => ($flag & $this->CONTACT_FLAG_VISIBLE?true:false),'shadow' => ($flag & $this->CONTACT_FLAG_SHADOW?true:false),'deleted' => ($flag & $this->CONTACT_FLAG_REMOVED?true:false),'id' => $i + 20,'client' => $client,'status2' => $status2,'xstatus' => $xstatus,'xstatus_text' => $xstatus_text);
            else
                $ret['unknown'][] = array('group' => $group,'addr' => $addr,'name' => $name,'status' => $status,'phone' => $phone,'not-authorized' => ($sflags & $this->CONTACT_INTFLAG_NOT_AUTHORIZED?true:false),'ignore' => ($flag & $this->CONTACT_FLAG_IGNORE?true:false),'invisible' => ($flag & $this->CONTACT_FLAG_INVISIBLE?true:false),'visible' => ($flag & $this->CONTACT_FLAG_VISIBLE?true:false),'shadow' => ($flag & $this->CONTACT_FLAG_SHADOW?true:false),'deleted' => ($flag & $this->CONTACT_FLAG_REMOVED?true:false),'id' => $i + 20,'client' => $client,'status2' => $status2,'xstatus' => $xstatus,'xstatus_text' => $xstatus_text);
            $i++;
        }
        return $ret;
    }
    /**
     * mrim::is_add_result()
     * 
     * Checks if incoming packet is add contact result
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_add_result($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_ADD_CONTACT_ACK)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        if($status == $this->CONTACT_OPER_GROUP_LIMIT)
            return 'group limit';
        if($status == $this->CONTACT_OPER_USER_EXISTS)
            return 'user exists';
        if($status == $this->CONTACT_OPER_INVALID_INFO)
            return 'invalid info';
        if($status == $this->CONTACT_OPER_NO_SUCH_USER)
            return 'no such user';
        if($status == $this->CONTACT_OPER_INTERR)
            return 'internal error';
        if($status == $this->CONTACT_OPER_ERROR)
            return 'error';
        if($status != $this->CONTACT_OPER_SUCCESS)
            return false;
        list(,$id) = unpack('V1',$packet[1]);
        return $id;
    }
    /**
     * mrim::is_modify_result()
     * 
     * Checks if incoming packet is modify contact result
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_modify_result($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MODIFY_CONTACT_ACK)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        if($status == $this->CONTACT_OPER_GROUP_LIMIT)
            return 'group limit';
        if($status == $this->CONTACT_OPER_USER_EXISTS)
            return 'user exists';
        if($status == $this->CONTACT_OPER_INVALID_INFO)
            return 'invalid info';
        if($status == $this->CONTACT_OPER_NO_SUCH_USER)
            return 'no such user';
        if($status == $this->CONTACT_OPER_INTERR)
            return 'internal error';
        if($status == $this->CONTACT_OPER_ERROR)
            return 'error';
        if($status != $this->CONTACT_OPER_SUCCESS)
            return false;
        return true;
    }
    /**
     * mrim::is_found users()
     * 
     * Checks if incoming packet is list of found users
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_found_users($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_ANKETA_INFO)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        if($status == $this->MRIM_ANKETA_INFO_STATUS_DBERR)
            return 'database error';
        if($status == $this->MRIM_ANKETA_INFO_STATUS_NOUSER)
            return 'no users';
        if($status == $this->MRIM_ANKETA_INFO_STATUS_RATELIMERR)
            return 'time limit';
        if($status != $this->MRIM_ANKETA_INFO_STATUS_OK)
            return 'error';
        $packet[1] = substr($packet[1],4);
        list(,$fnum) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        list(,$rows) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        list(,$timestamp) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        for($i = 0; $i < $fnum; $i++) {
            list(,$len) = unpack('V1',$packet[1]);
            $packet[1] = substr($packet[1],4);
            $fields[] = substr($packet[1],0,$len);
            $packet[1] = substr($packet[1],$len);
        }
        while(strlen($packet[1]) > 0) {
            for($i = 0; $i < $fnum; $i++) {
                list(,$len) = unpack('V1',$packet[1]);
                $packet[1] = substr($packet[1],4);
                $value = substr($packet[1],0,$len);
                $packet[1] = substr($packet[1],$len);
                $tmp[$fields[$i]] = $value;
            }
            $status = hexdec($tmp['mrim_status']);
            if($status == $this->STATUS_OFFLINE)
                $status = 'offline';
            elseif($status == $this->STATUS_ONLINE)
                $status = 'online';
            elseif($status == $this->STATUS_AWAY)
                $status = 'away';
            elseif($status & $this->STATUS_FLAG_INVISIBLE)
                $status = 'invisible';
            elseif($status == $this->STATUS_OTHER)
                $status = 'other';
            elseif($status == $this->STATUS_UNDETERMINATED)
                $status = 'undeterminated';
            else
                $status = 'unknown';
            $tmp['mrim_status'] = $status;
            $tmp['Nickname'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['Nickname']);
            $tmp['FirstName'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['FirstName']);
            $tmp['LastName'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['LastName']);
            $tmp['Location'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['Location']);
            $tmp['status_title'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['status_title']);
            $tmp['status_desc'] = iconv('UTF-16LE','UTF-8//IGNORE',$tmp['status_desc']);
            $ret['results'][] = $tmp;
        }
        $ret['fields_count'] = $fnum;
        $ret['max_rows'] = $rows;
        $ret['timestamp'] = $timestamp;
        return $ret;
    }
    /**
     * mrim::is_sms_report()
     * 
     * Checks if incoming packet is SMS status
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_sms_report($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_SMS_ACK)
            return false;
        list(,$status) = unpack('V1',$packet[1]);
        if($status == $this->SMS_ACK_DELIVERY_STATUS_INVALID_PARAMS)
            return 'invalid request';
        if($status == $this->SMS_ACK_SERVICE_UNAVAILABLE)
            return 'service unavailable';
        if($status != $this->SMS_ACK_DELIVERY_STATUS_SUCCESS)
            return false;
        return true;
    }
    /**
     * mrim::is_microblog_message()
     * 
     * Checks if incoming packet is somebody's microblog message
     * 
     * @access public
     * @param array $packet
     * @return mixed
     */
    function is_microblog_message($packet)
    {
        if(!is_array($packet))
            return false;
        if($packet[0] != $this->MRIM_CS_MICROBLOG_RECV)
            return false;
        $packet[1] = substr($packet[1],4);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $ret = array();
        $ret['addr'] = substr($packet[1],0,$len);
        $packet[1] = substr($packet[1],$len + 8);
        list(,$ret['time']) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        list(,$len) = unpack('V1',$packet[1]);
        $packet[1] = substr($packet[1],4);
        $ret['text'] = substr($packet[1],0,$len);
        return $ret;
    }
    /**
     * mrim::get_photo()
     * 
     * Downloads user's photo (avatar) from server
     * 
     * @access public
     * @param string $email
     * @param bool $big
     * @return mixed
     */
    function get_photo($email,$big = false)
    {
        list($name,$domain) = explode('@',$email);
        if(!$name || !$domain)
            return false;
        $domain = substr($domain,0,-3);
        if($domain == 'corp.mail')
            $domain = 'corp';
        $name = urlencode($name);
        if($big)
            $url = '/'.$domain.'/'.$name.'/_mrimavatar';
        else
            $url = '/'.$domain.'/'.$name.'/_mrimavatarsmall';
        if(!$this->proxy_type) {
            $fp = fsockopen('obraz.foto.mail.ru',80,$en,$es,$this->timeout);
            if(!$fp)
                return false;
            fputs($fp,"GET ".$url." HTTP/1.0\r\nHost: obraz.foto.mail.ru\r\nConnection: close\r\nUser-Agent: ".$this->user_agent."\r\n\r\n");
        } else {
            $fp = fsockopen($this->proxy,$this->proxy_port,$en,$es,$this->timeout);
            if(!$fp)
                return false;
            if($this->proxy_type == 'http') {
                if($this->proxy_user && $this->proxy_pass)
                    $au = "Proxy-Authorization: basic ".base64_encode($this->proxy_user.":".$this->proxy_pass)."\r\n";
                else
                    $au = '';
                $url = 'http://obraz.foto.mail.ru'.$url;
                fputs($fp,"GET ".$url." HTTP/1.0\r\nHost: obraz.foto.mail.ru\r\nConnection: close\r\nUser-Agent: ".$this->user_agent."\r\n".$au."\r\n");
            } elseif($this->proxy_type == 'socks4') {
                $ip = gethostbyname('obraz.foto.mail.ru');
                if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                    $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                else {
                    fclose($fp);
                    return false;
                }
                $request = pack('C2',0x04,0x01).pack('n',80).$int.($this->proxy_user)?$this->proxy_user:'0'.pack('C',0x00);
                fwrite($fp,$request);
                $resp = fread($fp,9);
                $answer = unpack('Cvn/Ccd',substr($resp,0,2));
                if($answer['vn'] != 0x00) {
                    fclose($fp);
                    return false;
                }
                if($answer['cd'] != 0x5A) {
                    fclose($fp);
                    return false;
                }
                fputs($fp,"GET ".$url." HTTP/1.0\r\nHost: obraz.foto.mail.ru\r\nConnection: close\r\nUser-Agent: ".$this->user_agent."\r\n\r\n");
            } elseif($this->proxy_type == 'socks5') {
                if($this->proxy_user && $this->proxy_pass)
                    $request = pack('C4',0x05,0x02,0x00,0x02);
                else
                    $request = pack('C3',0x05,0x01,0x00);
                fwrite($fp,$request);
                $resp = fread($fp,3);
                $answer = unpack('Cver/Cmethod',$resp);
                if($answer['method'] == 0x02) {
                    $request = pack('C',0x01).pack('C',strlen($this->proxy_user)).$this->proxy_user.pack('C',strlen($this->proxy_pass)).$this->proxy_pass;
                    fwrite($fp,$request);
                    $resp = fread($fp,3);
                    $answer = unpack('Cvn/Cresult',$resp);
                    if($answer['vn'] != 0x01 && $answer['result'] != 0x00) {
                        fclose($fp);
                        return false;
                    }
                }
                if($answer['method'] == 0x00) {
                    $ip = gethostbyname('obraz.foto.mail.ru');
                    if(preg_match('/(\\d+)\\.(\\d+)\\.(\\d+)\\.(\\d+)/',$ip,$matches))
                        $int = pack('C4',$matches[1],$matches[2],$matches[3],$matches[4]);
                    else {
                        fclose($fp);
                        return false;
                    }
                    $request = pack('C4',0x05,0x01,0x00,0x01).$int.pack('n',80);
                    fwrite($fp,$request);
                    $resp = fread($fp,11);
                    $answer = unpack('Cver/CREP',substr($resp,0,2));
                    if($answer['REP'] != 0x00) {
                        fclose($fp);
                        return false;
                    }
                    fputs($fp,"GET ".$url." HTTP/1.0\r\nHost: obraz.foto.mail.ru\r\nConnection: close\r\nUser-Agent: ".$this->user_agent."\r\n\r\n");
                } else
                    return false;
            }
        }
        if(substr(fgets($fp,1024),9,3) != '200') {
            fclose($fp);
            return false;
        }
        while(trim(fgets($fp,1024)) != '')
            ;
        $ret = '';
        while(!feof($fp))
            $ret .= fread($fp,1024);
        fclose($fp);
        return imagecreatefromstring($ret);
    }
    /**
     * mrim::form_links()
     * 
     * Form links to mail.ru projects
     * 
     * @access public
     * @param string $email
     * @return mixed
     */
    function form_links($email)
    {
        list($name,$domain) = explode('@',$email);
        if(!$name || !$domain)
            return false;
        $domain = substr($domain,0,-3);
        if($domain == 'corp.mail')
            $domain = 'corp';
        $name = urlencode($name);
        $ret['my'] = 'http://r.mail.ru/cln3587/my.mail.ru/'.$domain.'/'.$name;
        $ret['foto'] = 'http://r.mail.ru/cln3565/foto.mail.ru/'.$domain.'/'.$name;
        $ret['video'] = 'http://r.mail.ru/cln3567/video.mail.ru/'.$domain.'/'.$name;
        $ret['blogs'] = 'http://r.mail.ru/cln3566/blogs.mail.ru/'.$domain.'/'.$name;
        return $ret;
    }
    /**
     * mrim::crlf_validate()
     * 
     * Validates CR/LF strings end
     * 
     * @access private
     * @param string $str
     * @return string
     */
    function crlf_validate($str)
    {
        $str = str_replace("\r",'',$str);
        $str = str_replace("\n","\r\n",$str);
        return $str;
    }
    /**
     * mrim::decode_header()
     * 
     * Parses MIME headers
     * 
     * @access private
     * @param string $header
     * @return array
     */
    function decode_header($header)
    {
        $headers = explode("\r\n",$header);
        $decodedheaders = array();
        for($i = 0; $i < count($headers); $i++) {
            $thisheader = trim($headers[$i]);
            if(!empty($thisheader))
                if(!preg_match('#^[A-Z0-9a-z\\_\\-]+\\:#is',$thisheader))
                    $decodedheaders[$lasthead] .= " ".$thisheader;
                else {
                    $dbpoint = strpos($thisheader,':');
                    $headname = strtolower(substr($thisheader,0,$dbpoint));
                    $headvalue = trim(substr($thisheader,$dbpoint + 1));
                    if(isset($decodedheaders[$headname]) && $decodedheaders[$headname] != '')
                        $decodedheaders[$headname] .= '; '.$headvalue;
                    else
                        $decodedheaders[$headname] = $headvalue;
                    $lasthead = $headname;
                }
        }
        return $decodedheaders;
    }
    /**
     * mrim::decode_qp()
     * 
     * Decodes QP-encoded string
     * 
     * @access private
     * @param string $str
     * @return string
     */
    function decode_qp($str)
    {
        return preg_replace(array('#(=[0-9A-F]{2})#ie',"#=(\r\n|\n|\r)#"),array("chr(hexdec('\\1'))",''),$str);
    }
    /**
     * mrim::separate_headers()
     * 
     * Separates headers from message body
     * 
     * @access private
     * @param string $text
     * @return array
     */
    function separate_headers($text)
    {
        $arr = explode("\r\n\r\n",$text);
        $ret['headers'] = $arr[0]."\r\n\r\n";
        unset($arr[0]);
        $ret['body'] = implode("\r\n\r\n",$arr);
        return $ret;
    }
    /**
     * mrim::get_boundary()
     * 
     * Parses boundary from message headers
     * 
     * @access private
     * @param array $headers
     * @return mixed
     */
    function get_boundary($headers)
    {
        $ctype = $headers['content-type'];
        if(preg_match('/boundary[ ]?=[ ]?(["]?.*)/i',$ctype,$regs)) {
            $boundary = preg_replace('/^\\"(.*)\\"$/','\\1',$regs[1]);
            return '--'.trim($boundary);
        } elseif($headers['boundary'])
            return '--'.trim($headers['boundary']);
        else
            return false;
    }
    /**
     * mrim::split_parts()
     * 
     * Splits MIME message using boundary
     * 
     * @access private
     * @param string $boundary
     * @param string $body
     * @return array
     */
    function split_parts($boundary,$body)
    {
        $startpos = strpos($body,$boundary) + strlen($boundary) + 2;
        $lenbody = strpos($body,"\r\n".$boundary."--") - $startpos;
        $body = substr($body,$startpos,$lenbody);
        return explode($boundary."\r\n",$body);
    }
    /**
     * mrim::get_charset()
     * 
     * Parses charset from message headers
     * 
     * @access private
     * @param array $headers
     * @return mixed
     */
    function get_charset($headers)
    {
        $ctype = $headers['content-type'];
        if(preg_match('/charset[ ]?=[ ]?(["]?.*)/i',$ctype,$regs)) {
            $charset = preg_replace('/^\\"(.*)\\"$/','\\1',$regs[1]);
            return trim(strtoupper($charset));
        } else
            return false;
    }
    /**
     * mrim::open_db()
     * 
     * Loads geo-database to memory
     * 
     * @access public
     * @param string $file
     * @return bool
     */
    function open_db($file = 'base.db')
    {
        $fp = fopen($file,'r');
        if(!$fp)
            return false;
        $cnt = '';
        while(!feof($fp))
            $cnt .= fread($fp,1024);
        fclose($fp);
        preg_match_all('#([0-9]+)\\t([0-9]+)\\t([0-9]+)\\t([^\\r\\n]+)[\\r\\n]+#is',gzinflate($cnt),$this->base);
        unset($cnt,$this->base[0]);
        return true;
    }
    /**
     * mrim::list_db()
     * 
     * Returns array with names of countries/cities
     * 
     * @access public
     * @return mixed
     */
    function list_db()
    {
        if(!$this->base)
            return false;
        return $this->base[4];
    }
    /**
     * mrim::get_region_by_id()
     * 
     * Returns county/city name by global ID
     * 
     * @access public
     * @param int $id
     * @return mixed
     */
    function get_region_by_id($id)
    {
        if(!$this->base)
            return false;
        return $this->base[4][intval(array_search(intval($id),$this->base[1]))];
    }
    /**
     * mrim::get_country_by_id()
     * 
     * Returns county ID by global ID
     * 
     * @access public
     * @param int $id
     * @return mixed
     */
    function get_country_by_id($id)
    {
        if(!$this->base)
            return false;
        return $this->base[3][intval(array_search(intval($id),$this->base[1]))];
    }
    /**
     * mrim::get_city_by_id()
     * 
     * Returns city ID by global ID
     * 
     * @access public
     * @param int $id
     * @return mixed
     */
    function get_city_by_id($id)
    {
        if(!$this->base)
            return false;
        return $this->base[2][intval(array_search(intval($id),$this->base[1]))];
    }
    /**
     * mrim::get_id_by_region()
     * 
     * Returns global ID by country/city name
     * 
     * @access public
     * @param string $region
     * @return mixed
     */
    function get_id_by_region($region)
    {
        if(!$this->base)
            return false;
        return $this->base[1][intval(array_search($region,$this->base[4]))];
    }
    /**
     * mrim::close_db()
     * 
     * Unloads geo-database from memory
     * 
     * @access public
     * @return bool
     */
    function close_db()
    {
        if(!$this->base)
            return false;
        $this->base = null;
        return true;
    }
}
