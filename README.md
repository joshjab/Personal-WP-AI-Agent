# Personal-WP-AI-Agent 🤖
WP Snippet and Gradio code for adding your own personal chatbot to any Wordpress site

## Concept
This AI agent primarily based on the agent developed in the [agents](https://github.com/ed-donner/agents) course is added to a Wordpress site through the [Wordpress Snippets](https://wordpress.org/plugins/code-snippets/) plugin. It was really just a fun way to showcase some of the work that had been done through the tutorial. It takes in your LinkedIn profile page (as a PDF) and a brief summary.txt to describe other data you want the AI to know. 

## Installation
1. Clone the repository to your Wordpress server. `git clone https://github.com/joshjab/Personal-WP-AI-Agent`
2. `cd Personal-WP-AI-Agent`
2. Set up the Python environment with  `uv sync`
4. Modify your information in the `/me` folder and your name within the `app.py` file for the agent to be utilized with.
5. Create a file called `.env`, and add your OPENAI and PUSHOVER tokens if you want them:
```
OPENAI_API_KEY=sk-proj-*
PUSHOVER_USER=
PUSHOVER_TOKEN=
```
3. Initially run the Gradio agent  `uv run app.py` for testing purposes
2. Install [WP Snippets Plugin](https://wordpress.org/plugins/code-snippets/)
5. Create a new WP Snippet, paste `/wp-snippet.php` into it. Feel free to make modifications to the look and feel.
5. Server Dependent Step: The gradio application is purely local- not intended to allow for ports outside of the localhost to access it. You will likely need to proxy the gradio application (running on port 7860) to the default /chat, as well as some other things. Under Apache, I had to add this to all of the vhost files I had:
```
# === Gradio proxy – forward /chat/* and /queue/* to local Gradio ===
ProxyPass        /chat/  http://127.0.0.1:7860/
ProxyPassReverse /chat/  http://127.0.0.1:7860/

ProxyPassMatch   "^/queue/(.*)$"  "ws://127.0.0.1:7860/queue/$1"
ProxyPassReverse "^/queue/(.*)$"  "ws://127.0.0.1:7860/queue/$1"
# ===================================================================
```
4. Optional: Enable the App as a service by creating a file called /etc/systemd/chatbot.service. Copy the contents of `/chatbot.service` into it.
5. Optional:
``` 
sudo systemctl daemon-reload
sudo systemctl enable chatbot.service
sudo systemctl start chatbot.service
``` 
