Warning: the project is no longer being maintained due to the lack of applicability.

====================

Manul: a simple extensible antimalware tool for websites
====================
An utility which allows to analyze website logs made by Manul.

Warning: there is no auto-update in scanner. It is strongly recommended to remove the scanner from the server after scanning due to possible security issues.

====================
Log example:

```xml
<website_info>
    <server_environment>
        <script_filename>/home/www/mysite.com/web_root/pat/index.php</script_filename>
        <document_root>/home/www/mysite.com/web_root</document_root>
        <http_host>mysite.com</http_host>
        <admin_email>webmaster@mysite.com</admin_email>
        <time>2014.02.20 18:24:16</time>
        <server_addr>37.139.18.79</server_addr>
        <software>nginx/1.2.1</software>
        <server_gateway>CGI/1.1</server_gateway>
        <server_signature/>
        <server_hostname>badcode.tk</server_hostname>
        <platform_name>Linux 3.5.0-17-generic #28-Ubuntu SMP Tue</platform_name>
        <server_architecture>x86_64</server_architecture>
        <username>uid: 1000, gid: 1000</username>
        <path>/home/www/mysite.com/web_root/pat</path>
    </server_environment>
    <files>
        <file>
          <path>./wp-admin/css/colors/ectoplasm/colors-rtl.min.css</path>
          <size>40965</size>
          <ctime>1392903651</ctime>
          <mtime>1390501511</mtime>
          <owner>www-data</owner>
          <group>www-data</group>
          <access>0664</access>
          <md5>d148388c28a8d4c3b25b4c669849067f</md5>
        </file>
        <file pos="1261" snippet="CiAg...ycvJw== " detected="c">
            <path>./server_malware/swfobject.js</path>
            <size>1393</size>
            <ctime>1392903651</ctime>
            <mtime>1371623397</mtime> 
            <owner>pwnz0r</owner>
            <group>pwnz0rz</group> 
            <access>0664</access>
			<md5>497453e10b83c16186c7c3d31dc0a70d</md5>
       </file>
    </files>
</website_info>

```

Recipe example:

```xml
<recipe>
    <quarantine>./images/evil.php</quarantine> 
    <delete>./static/js/g00g1e-ana1ytics.js</delete>  
</recipe>

```

Licence
====================

BSD Licence
Copyright (c) 2013 

Peter Volkov, peter.r.volkov@yandex.ru

Greg Zemskov, ai@revisium.com

All rights reserved.


```
Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

  Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

  Redistributions in binary form must reproduce the above copyright notice, this
  list of conditions and the following disclaimer in the documentation and/or
  other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
```
