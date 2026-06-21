# Local Security Scanning Tools

## 1. JavaScript & Node.js Security

### njsscan
*   **Purpose:** Language-specific SAST scanner focused on Node.js, Express, and React Native applications. Finds prototype pollution, NoSQL injection, and misconfigurations.
*   **Installation:** Installed via `pip`.
*   **Scan Command:**
    ```bash
    njsscan .
    ```

### Semgrep (VS Code Extension)
*   **Purpose:** Provides real-time, pattern-matching security alerts directly inside the editor as you type.
*   **Installation:** Installed via the VS Code Extensions Marketplace.
*   **Scan Command:** Automated inside the IDE. (No command needed; check the **Problems** tab).

---

## 2. Python & Flask Security

### Bandit
*   **Purpose:** The de facto security linter for Python. Scans for Flask-specific vulnerabilities (like SSTI, raw SQL execution, and debug mode), insecure imports, and weak crypto.
*   **Installation:** Installed via `pip`.
*   **Scan Command:**
    ```bash
    bandit -r .
    ```

### Semgrep (VS Code Extension)
*   **Purpose:** Automatically extends to Python files to catch multi-language vulnerabilities, hardcoded secrets, and logic flaws in real-time.
*   **Installation:** Handled by the same VS Code extension.
*   **Scan Command:** Automated inside the IDE.
