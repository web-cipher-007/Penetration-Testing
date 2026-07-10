# Hidden Parameter Discovery Methodology

## 1. Goal

Discover undocumented input parameters accepted by an application through:

* Query parameters
* Body parameters
* JSON/XML fields
* Form fields
* Headers
* Cookies
* Client-side-only parameters
* Legacy or archived parameters

Hidden parameters often come from debug flags, internal/admin features, legacy endpoints, and undocumented functionality. These are interesting because they may be less validated than normal user-facing inputs.

---

## 2. Prepare Scope

Before testing, define:

```md
Target:
- Main domain:
- Subdomains:
- API hosts:
- Mobile/API endpoints:
- Authenticated roles:
- Out-of-scope paths:
- Rate limits:
- Allowed testing window:
```

Set up:

* Burp Suite or ZAP
* Browser with proxy configured
* Authenticated test accounts
* Wordlists
* Parameter discovery tools:

  * Arjun
  * x8
  * ParamMiner
  * GAP
  * ffuf, optional

---

## 3. Capture Normal Application Traffic

Browse the application like a real user.

Cover:

* Login/logout
* Profile update
* Search
* Filters
* Checkout/payment flow, if in scope
* Admin/user dashboards
* Uploads
* API-heavy actions
* Mobile app traffic, if available

For every request, save:

```md
URL:
Method:
Content-Type:
Existing parameters:
Headers:
Cookies:
Response status:
Response length:
Response hash:
Role/account used:
Notes:
```

Export Burp/ZAP history. This becomes your baseline.

---

## 4. Passive HTML Parameter Extraction

Inspect all crawled HTML pages.

Look for:

```html
<input name="...">
<input id="...">
<textarea name="...">
<select name="...">
<button name="...">
<meta name="...">
<div id="...">
```

The Intigriti post recommends scraping `id` and `name` attributes from HTML elements because they often reveal real parameter names, including non-obvious fields.

Prioritize:

* Hidden inputs
* Disabled fields
* Read-only fields
* IDs that look like database fields
* Role/admin-related names
* Tracking/debug/state fields

Example candidate list:

```txt
user_id
account_id
role
is_admin
debug
redirect
next
return_url
csrf
token
plan
status
price
discount
```

Burp Suite and ZAP can also help highlight hidden input fields. Hidden form fields are often used to preserve state between page loads, making them useful places to inspect carefully.

---

## 5. JavaScript File Enumeration

Collect all JavaScript files from:

* HTML source
* Burp history
* Source maps
* Webpack chunks
* API frontend bundles
* Archived JS files

Search for:

```js
fetch(
axios(
XMLHttpRequest
URLSearchParams
FormData
.append(
.get(
params.
query.
body.
payload.
location.search
window.location
```

JavaScript files often contain API routes, app routes, and input parameters. The blog specifically recommends checking function calls that parse or retrieve parameters.

Extract:

```md
Endpoints:
- /api/user/update
- /api/order/apply-discount

Parameter names:
- userId
- discountCode
- type
- source
- redirectUrl
```

Also collect variable names. The blog notes that even variable names can be useful candidates to try as body or query parameters.

Example:

```js
const isInternalUser = false
const previewMode = true
const cacheBypass = 1
```

Candidate params:

```txt
isInternalUser
previewMode
cacheBypass
internal_user
preview_mode
cache_bypass
```

---

## 6. Client-Side Parameter Interception

Test whether the frontend reads unknown parameters from the URL.

Example:

```txt
https://target.com/page?testparam=tilak123
```

Observe:

* DOM changes
* Console logs
* Reflections
* Different client-side routing
* Hidden UI becoming visible
* API calls triggered by the parameter

The blog mentions tools like Eval Villain for detecting when supplied parameters are processed by the DOM, which can indicate possible DOM-based issues.

---

## 7. Search Engine, GitHub, and Archive Enumeration

Use public indexed data to find old or hidden parameter names.

Search engine dorks:

```txt
site:target.com inurl:?
site:target.com inurl:&
site:target.com inurl:redirect
site:target.com inurl:return
site:target.com inurl:debug
```

The blog recommends Google dorking and similar searches on Bing, DuckDuckGo, Yandex, GitHub, and other platforms to discover URLs containing parameters.

Look for:

* Old URLs
* Staging references
* Deprecated APIs
* Shared links
* Query-heavy pages
* Public GitHub code
* Old documentation
* Leaked frontend routes

Wayback Machine workflow:

```md
1. Search the target domain.
2. Filter archived URLs containing `?` or `&`.
3. Extract parameter names.
4. Check old JS files.
5. Compare old params with current endpoints.
```

The Internet Archive is useful because it stores older versions of pages and JavaScript files, which may expose legacy parameters no longer visible in the current UI.

---

## 8. Build a Custom Parameter Wordlist

Create one master candidate list.

Sources:

```md
- HTML `name` attributes
- HTML `id` attributes
- Hidden form fields
- JavaScript variable names
- API request bodies
- Existing query parameters
- Existing JSON keys
- GitHub results
- Wayback URLs
- Mobile app traffic
- Burp/ZAP history
- Business-specific terms
```

Normalize casing:

```txt
user_id
userId
userid
user-id
UserId
USER_ID
```

Add common sensitive/debug variants:

```txt
debug
test
admin
is_admin
role
access
internal
preview
bypass
cache
no_cache
trace
verbose
token
redirect
next
return
callback
url
path
file
id
user_id
account_id
org_id
tenant_id
```

The blog recommends combining custom target-specific wordlists with generic parameter wordlists for better accuracy.

---

## 9. Active Parameter Fuzzing

Now actively test whether the backend processes unknown parameters.

The goal is not exploitation yet. The goal is to detect response differences.

Check for changes in:

```md
- Status code
- Response length
- Response body
- Headers
- Redirect location
- Error messages
- Reflections
- Timing
- Cache behavior
- JSON structure
- Permission behavior
```

Parameter fuzzing works by observing response changes caused by candidate parameters. According to the blog, this is one of the most accurate and scalable ways to discover hidden/unreferenced parameters.

---

## 10. Arjun Workflow

Install:

```bash
pipx install arjun
```

Arjun is an HTTP parameter discovery suite. It supports GET, POST, JSON, and XML methods, custom headers, imported targets, passive collection, rate limiting, and JSON/text/Burp exports.

Single URL:

```bash
arjun -u https://target.com/api/profile
```

POST parameters:

```bash
arjun -u https://target.com/api/profile -m POST
```

JSON parameters:

```bash
arjun -u https://target.com/api/profile -m JSON
```

Custom wordlist:

```bash
arjun -u https://target.com/api/profile -w params.txt
```

Export JSON:

```bash
arjun -u https://target.com/api/profile -oJ arjun-result.json
```

Rate limit:

```bash
arjun -u https://target.com/api/profile --ratelimit 2
```

Stable mode:

```bash
arjun -u https://target.com/api/profile --stable
```

With headers:

```bash
arjun -u https://target.com/api/profile --headers "Cookie: session=YOUR_COOKIE"
```

Arjun usage docs confirm `-u` for single URL, `-m` for method, `-oJ` for JSON export, `-w` for custom wordlists, and `--ratelimit` / `--stable` for safer pacing.

---

## 11. x8 Workflow

x8 is a Rust-based hidden parameter discovery tool. It compares response differences such as line-by-line changes, status codes, and reflections.

Basic query parameter discovery:

```bash
x8 -u "https://target.com/page" -w params.txt
```

Existing parameter context:

```bash
x8 -u "https://target.com/page?known=1" -w params.txt
```

POST body discovery:

```bash
x8 -u "https://target.com/api/profile" -X POST -w params.txt
```

Custom JSON body template:

```bash
x8 -u "https://target.com/api/profile" -X POST -b '{"%s":"test"}' -w params.txt
```

Header discovery:

```bash
x8 -u "https://target.com" --headers -w params.txt
```

x8 supports custom templates, body fuzzing, header discovery, and query testing.

---

## 12. Burp ParamMiner Workflow

Use ParamMiner when you already have good Burp traffic.

Workflow:

```md
1. Install ParamMiner from Burp BApp Store.
2. Capture authenticated traffic.
3. Right-click request.
4. Choose:
   - Guess params
   - Guess headers
   - Guess cookies
5. Review ParamMiner output.
6. Manually confirm each finding.
```

ParamMiner identifies hidden/unlinked parameters and can guess cookies, headers, and params from Burp requests. It also harvests words from in-scope traffic.

Use it especially for:

* Cache poisoning research
* Header-based hidden behavior
* Cookie-based feature flags
* Authenticated endpoints
* Large Burp histories

---

## 13. GAP Workflow

Use GAP to passively collect parameters/endpoints and generate target-specific wordlists.

Workflow:

```md
1. Install GAP Burp extension.
2. Browse the target normally.
3. Let GAP collect endpoints and params.
4. Export generated wordlist.
5. Feed the wordlist into Arjun/x8/ffuf.
```

GAP is designed to find potential endpoints, parameters, and generate custom target wordlists.

---

## 14. Parameter Reuse Testing

Test discovered parameters across other endpoints.

Example:

```txt
Found on:
/api/profile?debug=true

Try safely on:
/api/orders?debug=true
/api/search?debug=true
/api/admin?debug=true
/api/export?debug=true
```

The blog notes that query and body parameters are sometimes reused across multiple routes and API endpoints, so parsed parameters from crawl history should be tested on different endpoints.

Also check mobile apps. The blog recommends inspecting mobile app source/traffic for URLs or links pointing to the web app with parameters.

---

## 15. Validation Process

For every discovered parameter, confirm it manually.

Use this checklist:

```md
Parameter:
Endpoint:
Method:
Location:
- Query
- Body
- JSON
- Header
- Cookie

Baseline response:
Response with parameter:
Difference:
Confirmed processed?
Security impact?
Needs auth?
Works across roles?
Works cross-account?
Works on other endpoints?
```

Test safely:

```txt
?candidate=test123
?candidate=1
?candidate=true
?candidate=false
?candidate=null
?candidate[]=test
```

Check for:

```md
- Reflection
- Error message
- Boolean behavior
- Access control changes
- Debug output
- Different data returned
- Feature unlock
- Redirect behavior
- File/path behavior
- Cache key changes
```

---

## 16. Prioritization

High-priority parameter names:

```txt
admin
is_admin
role
permission
access
user_id
account_id
org_id
tenant_id
debug
trace
verbose
preview
internal
redirect
next
url
path
file
callback
return
price
discount
plan
status
approved
verified
```

High-priority endpoints:

```md
- Auth endpoints
- User/profile endpoints
- Admin endpoints
- Search endpoints
- Export/download endpoints
- Payment/checkout endpoints
- Upload endpoints
- Webhook endpoints
- Redirect endpoints
- Cacheable pages
- API endpoints returning user data
```

---

## 17. Reduce False Positives

Before reporting, eliminate noise.

Repeat each test:

```md
1. Send baseline request 3 times.
2. Send parameter request 3 times.
3. Compare stable differences.
4. Change parameter value.
5. Confirm behavior changes with value.
6. Test without auth.
7. Test with lower-privilege auth.
8. Test on another account.
```

Ignore differences caused only by:

```md
- Timestamp
- CSRF token
- Random IDs
- Tracking IDs
- Ads/analytics
- Cache variance
- Rate-limit pages
- A/B testing
```

---

## 18. Final Output Format

Use this table for tracking:

```md
| Endpoint | Method | Param | Location | Source | Evidence | Impact | Status |
|---|---|---|---|---|---|---|---|
| /api/profile | GET | debug | query | JS + fuzzing | shows extra field | info leak | confirmed |
| /checkout | POST | discount | JSON | HTML + fuzzing | price changed | business logic | needs validation |
```

Use this format for each confirmed finding:

````md
## Finding: Hidden parameter accepted on `<endpoint>`

### Summary
The endpoint accepts an undocumented parameter named `<param>`.

### Endpoint
`METHOD /path`

### Parameter Location
Query / Body / JSON / Header / Cookie

### Evidence
Baseline request:

```http
GET /path HTTP/1.1
Host: target.com
````

Modified request:

```http
GET /path?param=test HTTP/1.1
Host: target.com
```

### Observed Difference

* Status changed:
* Response length changed:
* Reflected value:
* New field:
* Permission behavior:

### Security Impact

Explain what the parameter allows.

### Reproduction Steps

1. Login as low-privilege user.
2. Send baseline request.
3. Add hidden parameter.
4. Observe difference.

### Remediation

* Validate undocumented parameters.
* Remove unused legacy/debug parameters.
* Enforce server-side authorization.
* Reject unknown parameters where possible.
* Add tests for hidden/internal flags.

````

---

## 19. Recommended Workflow Summary

```md
1. Crawl target manually and with proxy.
2. Export all requests.
3. Extract parameters from HTML.
4. Extract parameters from JavaScript.
5. Collect variables and route names.
6. Search Google/GitHub/Wayback.
7. Build custom wordlist.
8. Merge with generic wordlist.
9. Fuzz query/body/JSON/header/cookie locations.
10. Reuse found parameters across endpoints.
11. Validate manually.
12. Remove false positives.
13. Test impact safely.
14. Report only confirmed behavior.
````

---

## 20. Key Mindset

Do not only look for parameters that already exist in requests.

Look for names developers accidentally reveal through:

```md
- HTML
- JavaScript
- Old URLs
- Archived files
- Variable names
- Mobile traffic
- Burp history
- Reused backend patterns
```

Hidden parameter discovery is mainly about finding where the application still listens, even when the UI no longer talks.


Referenced from: [blog](https://www.intigriti.com/researchers/blog/hacking-tools/finding-hidden-input-parameters) 

