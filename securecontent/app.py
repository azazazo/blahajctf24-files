from flask import Flask, Response, request, render_template_string, render_template
import subprocess, shlex
from waitress import serve

app = Flask(__name__)
ipport = "127.0.0.1:8000"
@app.after_request
def apply_csp(response: Response) -> Response:
    csp = (
        "connect-src 'none'; "
        "font-src 'none'; "
        "frame-src 'none'; "
        "img-src 'self'; "
        "manifest-src 'none'; "
        "media-src 'none'; "
        "object-src 'none'; "
        "script-src 'none'; "
        "worker-src 'none'; "
        "style-src 'self'; "
        "frame-ancestors 'none'; "
        "block-all-mixed-content;"
        "require-trusted-types-for 'script';"
    )
    response.headers['Content-Security-Policy'] = csp
    return response
def generatenamepage(name):
    return """<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hello!</title>
    </head>
    <body>
        <div>
            <p>Hello, """+name+"""! I hope you like flags! In fact, here is a flag: blahaj{[FLAG REDACTED]}. Sadly, only the admin bot can see it :'(<p>
        </div>
    </body>
    </html>"""
@app.route('/greet')
def greet():
    name = request.args.get('name', '')
    if request.remote_addr == "127.0.0.1":
        # adminbot can see real flag
        return generatenamepage(name).replace("{[FLAG REDACTED]}", "{[still redacted lol]}")
    else:
        return generatenamepage(name)

@app.route('/')
def index():
    return render_template_string('''
        <center><h1>Make a greeting!</h1>
        <form action="/greet" method="get">
            <label for="name">Enter your name:</label><br>
            <input type="text" id="name" name="name" required><br><br>
            <input type="submit" value="Greet">
        </form>
        <h6>Do you see someone having a bad name? Report it <a href="/report">here</a></h6></center>
    ''')

@app.route('/report')
def report():
    return render_template_string('''
        <h1>Report a name</h1>
        <form action="/adminbot" method="post">
            <label for="url">Enter URL (must start with http://'''+ipport+'''/):</label><br>
            <input type="text" id="url" name="url" required><br><br>
            <input type="submit" value="Report it">
        </form>
    ''')

@app.route('/adminbot', methods=['POST'])
def adminbot():
    url = request.form.get('url')
    
    if not url or not url.startswith('http://'+ipport+'/'):
        return "Invalid URL. It must start with 'http://"+ipport+"/'.", 400
    
    command = f"chromium --virtual-time-budget=10000 --no-sandbox --headless --disable-gpu --timeout=5000 {shlex.quote(url)}"
    subprocess.Popen(command, shell=True)
    return "Admin bot will see your request soon"

if __name__ == '__main__':
    serve(app, host='0.0.0.0', port=8000)