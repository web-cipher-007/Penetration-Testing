# 🚀 Naabu Port Scanning Cheat Sheet
### *A comprehensive cheat list of professional \`naabu\` commands and optimizations.*

---

<p align="center">
  <img src="https://img.shields.io/badge/Tool-Naabu-blue?style=for-the-badge&logo=terminal" alt="Naabu"/>
  <img src="https://img.shields.io/badge/Level-Advanced-red?style=for-the-badge" alt="Advanced"/>
  <img src="https://img.shields.io/badge/Purpose-Port%20Scanning-brightgreen?style=for-the-badge" alt="Port Scanning"/>
</p>

---

## 📋 Table of Contents
| # | Command | Description |
|---|---------|-------------|
| 1 | [Basic Target & Speed Management](#1-basic-target--speed-management) | Optimized mass scanning |
| 2 | [Quiet Statistical Smart Scanning](#2-quiet-statistical-smart-scanning) | Predictive port discovery |
| 3 | [Excluding Cloud Firewalls](#3-excluding-cloud-firewalls-wafcdn) | CDN/WAF evasion |
| 4 | [Protocol-Accurate UDP Probes](#4-protocol-accurate-udp-probes) | UDP service discovery |
| 5 | [Host Discovery](#5-host-discovery-bypassing-ping-blockers) | Ping bypass techniques |
| 6 | [Streaming to Pipeline Automations](#6-streaming-to-pipeline-automations) | Tool chaining |
| 7 | [Multi-IP Cluster Scanning](#7-multi-ip-cluster-scanning) | Load balancer enumeration |
| 8 | [Exporting Structured Data](#8-exporting-structured-data) | JSON output generation |

---

## 1️⃣ Basic Target & Speed Management
> Scan a massive list of targets efficiently using optimized speed parameters.

```bash
naabu -list targets.txt -top-ports 1000 -c 50 -rate 3000
```

| Flag | Value | Purpose |
|------|-------|---------|
| \`-list\` | \`targets.txt\` | Input file containing targets |
| \`-top-ports\` | \`1000\` | Most common ports to scan |
| \`-c\` | \`50\` | Concurrent workers |
| \`-rate\` | \`3000\` | Packets per second |

**💡 Description:** Scans the top 1000 most common ports across a list of domains/IPs from \`targets.txt\`. Uses 50 concurrent workers (\`-c 50\`) and a highly stable network packet rate of 3,000 packets per second (\`-rate 3000\`) to prevent dropped packets and bottlenecks.

⏱️ *[00:03:38]*

---

## 2️⃣ Quiet Statistical Smart Scanning
> Stay under the radar using predictive modeling.

```bash
naabu -smart -probability 20
```

| Flag | Value | Purpose |
|------|-------|---------|
| \`-smart\` | — | Enable predictive scanning |
| \`-probability\` | \`20\` | Minimum confidence threshold |

**💡 Description:** Evaluates a few initial ports and mathematically predicts other open ports nearby based on detected architecture (e.g., if MySQL on 3306 is open, it predicts web panels on 8080). The \`-probability 20\` flag tells Naabu to only scan extra ports if it has a 20% or higher confidence that they are open.

⏱️ *[00:05:29]*

---

## 3️⃣ Excluding Cloud Firewalls (WAF/CDN)
> Avoid wasting time or alerting global defensive systems like Cloudflare.

```bash
naabu -exclude-cdn
```

| Flag | Purpose |
|------|---------|
| \`-exclude-cdn\` | Skip CDN/WAF protected IPs |

**💡 Description:** Checks targets against a known CDN database in real-time. If it finds a CDN/WAF IP, it skips full multi-port scans and only checks standard web ports (80/443). True origin IPs still get fully scanned.

⏱️ *[00:06:56]*

---

## 4️⃣ Protocol-Accurate UDP Probes
> Uncover hidden services often ignored by standard TCP scanners.

```bash
naabu -udp
```

| Flag | Purpose |
|------|---------|
| \`-udp\` | Enable UDP scanning |

**💡 Description:** Crafts highly specific, protocol-accurate payloads pulled from Nmap's database (e.g., proper DNS queries on port 53 or SNMP on 161) to trick connectionless UDP services into replying.

⏱️ *[00:08:10]*

---

## 5️⃣ Host Discovery (Bypassing Ping Blockers)
> Map active networks quickly when standard ICMP pings are blocked by firewalls.

```bash
naabu -host-discovery -sn -ping -p 80
```

| Flag | Purpose |
|------|---------|
| \`-host-discovery\` | Enable host discovery mode |
| \`-sn\` | Skip port scanning |
| \`-ping\` | Send ICMP echo requests |
| \`-p 80\` | Target specific port |

**💡 Description:** Skips port scanning entirely to find live hosts. It combines a standard ICMP echo request with a TCP SYN packet to port 80 to bypass restrictive firewalls.

⏱️ *[00:09:18]*

---

## 6️⃣ Streaming to Pipeline Automations
> Chain tools together to immediately analyze discovered doors.

```bash
naabu -silent | httpx
```

| Flag | Purpose |
|------|---------|
| \`-silent\` | Suppress banner and logos |

**💡 Description:** Suppresses the banner text and extra logos with \`-silent\`, outputting only raw IP/port strings. This clean output is piped (\`|\`) into \`httpx\` to instantly capture webpage titles and detect tech stacks (like Apache or WordPress).

⏱️ *[00:10:18]*

---

## 7️⃣ Multi-IP Cluster Scanning
> Scan all backend endpoints when load balancers distribute a single domain across multiple locations.

```bash
naabu -scan-all-ips
```

| Flag | Purpose |
|------|---------|
| \`-scan-all-ips\` | Scan all resolved IPs |

**💡 Description:** Queries DNS records to pull out every single IP address mapped to a round-robin DNS setup or load balancer and scans them concurrently, ensuring you don't miss legacy/development servers.

⏱️ *[00:11:20]*

---

## 8️⃣ Exporting Structured Data
> Generate script-ready logs instead of relying on terminal screens.

```bash
naabu -json -output results.json
```

| Flag | Value | Purpose |
|------|-------|---------|
| \`-json\` | — | Enable JSON output format |
| \`-output\` | \`results.json\` | Output file name |

**💡 Description:** Saves the scan results to a structured \`results.json\` file. Perfect for parsing with custom Python scripts later to target specific open avenues (like port 443) automatically.

⏱️ *[00:12:14]*

---

## 📊 Quick Reference Card

| Command | Use Case |
|---------|----------|
| \`naabu -list targets.txt -top-ports 1000 -c 50 -rate 3000\` | Mass scanning |
| \`naabu -smart -probability 20\` | Stealth scanning |
| \`naabu -exclude-cdn\` | CDN evasion |
| \`naabu -udp\` | UDP discovery |
| \`naabu -host-discovery -sn -ping -p 80\` | Host discovery |
| \`naabu -silent \\| httpx\` | Pipeline automation |
| \`naabu -scan-all-ips\` | Load balancer scanning |
| \`naabu -json -output results.json\` | JSON export |

---

## 🛠️ Pro Tips
- 🎯 Combine \`-silent\` with \`-json\` for clean, script-ready output
- 🔄 Use \`-rate\` carefully—too high may trigger IDS/IPS alerts
- 🚀 Start with \`-top-ports 1000\` for a balance of speed and coverage
- 📝 Always save results with \`-output\` for later analysis

---

## 🔗 Useful Resources
- [Official Naabu Repository](https://github.com/projectdiscovery/naabu)
- [ProjectDiscovery Documentation](https://docs.projectdiscovery.io/tools/naabu)
- [Nmap Port Reference](https://nmap.org/book/port-scanning.html)

---

<p align="center">
  <b>Happy Scanning! 🔍</b><br>
  <sub>Remember to always scan responsibly and with proper authorization.</sub>
</p>
