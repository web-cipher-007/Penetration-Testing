# Google Dorks Reference Sheet
> A categorized collection of search operators for finding exposed files, credentials, misconfigurations, and sensitive data. For authorized security research and OSINT only.

---

## Core Operators

| Operator | Description |
|----------|-------------|
| `site:` | Restrict results to a specific domain (e.g. `site:*.example.com`) |
| `intitle:` | Search for a keyword in the page title |
| `inurl:` | Search for a keyword in the URL |
| `filetype:` / `ext:` | Find specific file types (`pdf`, `log`, `sql`, `env`, `yml`, etc.) |
| `intext:` | Search for a keyword in the page body text |
| `cache:` | Show Google's cached version of a URL |
| `"quotes"` | Exact phrase match — useful for finding specific strings, error messages, or keys |
| `OR` | Logical OR between two terms (e.g. `ext:env OR ext:yml`) |
| `-term` | Exclude a term from results (e.g. `-inurl:docs`) |
| `*` | Wildcard — matches any subdomain or word (e.g. `site:*.example.com`) |
| `..` | Number range (e.g. `port 8000..9000`) |
| `related:` | Find sites related to a given domain |

---

## 1. Credentials & Secrets

| Query | Purpose |
|-------|---------|
| `site:*.target.com "api_key" OR "api_secret"` | Find exposed API credentials in page content |
| `site:*.target.com "-----BEGIN RSA PRIVATE KEY-----"` | Detect exposed private RSA keys |
| `site:*.target.com "-----BEGIN OPENSSH PRIVATE KEY-----"` | Find exposed OpenSSH private keys |
| `site:*.target.com "password" filetype:log` | Look for passwords leaked in log files |
| `site:github.com "target.com" "api_key"` | Search GitHub repos for leaked API keys |
| `site:github.com "target.com" password` | Search GitHub for leaked passwords |
| `site:pastebin.com "target.com" password` | Find leaked credentials on Pastebin |
| `site:trello.com "target.com" password` | Search Trello boards for exposed credentials |
| `"db_password" OR "db_pass" filetype:env` | Find `.env` files with database passwords |
| `inurl:"/wp-config.php" "DB_PASSWORD"` | Exposed WordPress config files with DB credentials |

---

## 2. Sensitive Files & Configuration

| Query | Purpose |
|-------|---------|
| `site:*.target.com filetype:log` | Find exposed log files on a domain |
| `site:*.target.com ext:env OR ext:yml OR ext:json` | Find config files (`.env`, YAML, JSON) |
| `site:*.target.com ext:sql` | Find exposed SQL database dump files |
| `site:*.target.com ext:bak OR ext:old OR ext:backup` | Find backup files accidentally left on servers |
| `site:*.target.com filetype:xml inurl:config` | Locate XML config files |
| `site:*.target.com filetype:conf` | Find `.conf` server configuration files |
| `site:*.target.com filetype:properties` | Find Java `.properties` files (common in Spring Boot) |
| `site:*.target.com filetype:pem` | Find exposed PEM certificate/key files |
| `site:*.target.com "docker-compose.yml"` | Find exposed Docker Compose files |
| `"Dockerfile" site:*.target.com` | Find Dockerfiles in public-facing directories |

---

## 3. Exposed Directories & Admin Panels

| Query | Purpose |
|-------|---------|
| `site:*.target.com intitle:"index of /"` | Find open directory listings |
| `site:*.target.com intitle:"index of" "parent directory"` | Find parent directory indexes |
| `site:*.target.com inurl:admin` | Discover admin panel pages |
| `site:*.target.com inurl:login` | Find login portals |
| `site:*.target.com inurl:dashboard` | Find exposed dashboards |
| `site:*.target.com inurl:cpanel OR inurl:plesk` | Locate hosting control panels |
| `site:*.target.com intitle:"phpMyAdmin"` | Find exposed phpMyAdmin database interfaces |
| `intitle:"Kibana" inurl:5601` | Find exposed Kibana log analytics dashboards |
| `intitle:"Grafana" inurl:3000` | Find exposed Grafana monitoring dashboards |
| `intitle:"Jenkins" inurl:8080` | Find exposed Jenkins CI/CD servers |

---

## 4. API & Technology Discovery

| Query | Purpose |
|-------|---------|
| `site:*.target.com inurl:swagger \| inurl:api-docs` | Find Swagger/OpenAPI documentation |
| `site:*.target.com intitle:api` | Find pages with "api" in the title |
| `site:*.target.com inurl:v1 OR inurl:v2 OR inurl:v3` | Enumerate versioned API endpoints |
| `site:*.target.com inurl:phpinfo.php` | Find exposed PHP configuration pages |
| `site:*.target.com inurl:git` | Find exposed `.git` directory remnants |
| `site:*.target.com inurl:.git/config` | Find exposed `.git` config files directly |
| `site:*.target.com inurl:actuator` | Find exposed Spring Boot Actuator endpoints |
| `site:*.target.com inurl:graphql` | Find exposed GraphQL endpoints |
| `site:*.target.com intext:"wp-content/plugins"` | Identify WordPress plugins in use |
| `site:*.target.com "X-Powered-By: Express"` | Identify Node.js Express-powered applications |

---

## 5. Leaked Data on Third-Party Sites

| Query | Purpose |
|-------|---------|
| `site:pastebin.com "target.com"` | Search Pastebin for any mentions of a domain |
| `site:github.com "target.com" "api_key"` | Search GitHub for API keys tied to a domain |
| `site:github.com "target.com" "secret"` | Search GitHub for any secrets tied to a domain |
| `site:gitlab.com "target.com" password` | Search GitLab for leaked passwords |
| `site:trello.com "target.com"` | Find Trello boards mentioning a domain |
| `site:jira.*.com "target.com"` | Find Jira tickets referencing a domain |
| `site:s3.amazonaws.com "target.com"` | Find publicly accessible S3 buckets |
| `site:storage.googleapis.com "target.com"` | Find exposed Google Cloud Storage buckets |
| `site:docs.google.com "target.com" "password"` | Look for leaked credentials in public Google Docs |
| `site:npmjs.com "target.com"` | Find npm packages linked to a domain (supply chain recon) |

---

## 6. Network Devices, IoT & Industrial Systems

| Query | Purpose |
|-------|---------|
| `intitle:"Router Login" inurl:login.asp` | Find exposed router login pages |
| `intitle:"webcam" inurl:view.shtml` | Find exposed webcam feeds |
| `intitle:"Network Camera" inurl:main.cgi` | Find exposed network cameras |
| `inurl:"/level/15/exec/-/show" "cisco"` | Find exposed Cisco IOS exec command pages |
| `intitle:"SCADA" inurl:index.php` | Locate industrial SCADA systems online |
| `intitle:"VNC viewer for Java"` | Find exposed VNC viewer interfaces |
| `intitle:"Remote Desktop Web Connection"` | Find exposed RDP web portals |
| `inurl:8080 intitle:"webcam"` | Common webcam port exposure check |
| `intitle:"HP LaserJet" inurl:hp/device/this.LCDispatcher` | Locate exposed HP network printers |
| `intitle:"D-Link" inurl:login.cgi` | Find exposed D-Link device login pages |

---

## 7. Login Portals & Authentication

| Query | Purpose |
|-------|---------|
| `site:*.target.com inurl:login` | General login pages on a domain |
| `site:*.target.com inurl:signin` | Sign-in pages on a domain |
| `site:*.target.com inurl:auth` | Authentication-related pages |
| `site:*.target.com inurl:sso` | SSO/Single Sign-On portals |
| `site:*.target.com intitle:"Outlook Web App"` | Find exposed OWA/Outlook Web portals |
| `site:*.target.com intitle:"Citrix"` | Find Citrix remote access portals |
| `site:*.target.com inurl:vpn OR inurl:remote` | Find VPN and remote access pages |
| `site:*.target.com intitle:"FortiGate"` | Find FortiGate VPN login pages |
| `site:*.target.com intitle:"GlobalProtect"` | Find Palo Alto GlobalProtect VPN portals |
| `site:*.target.com intitle:"Pulse Secure"` | Find Pulse Secure VPN portals |

---

## 8. Error Messages & Debug Information

| Query | Purpose |
|-------|---------|
| `site:*.target.com "SQL syntax" OR "mysql_fetch"` | Find pages leaking SQL error messages |
| `site:*.target.com "Warning: mysql_" OR "ORA-"` | Find MySQL/Oracle DB error leaks |
| `site:*.target.com intitle:"500 Internal Server Error"` | Find 500 error pages with debug info |
| `site:*.target.com "PHP Parse error" OR "PHP Fatal error"` | Find PHP error messages with file paths |
| `site:*.target.com "stack trace" OR "at line"` | Find stack traces revealing code structure |
| `site:*.target.com "error in your SQL syntax"` | Classic SQL injection error indicator |
| `site:*.target.com intitle:"Test Page for Apache"` | Find default Apache test pages |
| `site:*.target.com intitle:"Welcome to nginx"` | Find default nginx pages (unconfigured servers) |
| `site:*.target.com "X-Debug-Token"` | Find Symfony debug token exposure |
| `site:*.target.com inurl:"/_error/" OR inurl:"/debug/"` | Find debug and error routes exposed publicly |

---

> ⚠️ **Legal Notice:** Use these techniques only on systems you own or have explicit written authorization to test. Unauthorized scanning or probing may violate laws in your jurisdiction.
