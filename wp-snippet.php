/**
 * Front‑end Gradio chat bubble (offline‑friendly test version)
 * Requires the Code Snippets plugin.
 */
add_action( 'wp_footer', function () { ?>
  <script type="module">
    /* ----------  Build the UI right away  ---------- */
    const bubble = Object.assign(document.createElement('button'), { textContent:'💬\nChat w/ AI' });
    Object.assign(bubble.style,{
      position:'fixed',bottom:'24px',right:'24px',padding:'12px 14px',
      fontSize:'24px',borderRadius:'50%',border:'none',cursor:'pointer',
      background:'#2563eb',color:'#fff',zIndex:9999
    });
    document.body.appendChild(bubble);

    const panel = document.createElement('div');
    Object.assign(panel.style,{
      display:'none',position:'fixed',bottom:'80px',right:'24px',width:'320px',
      maxHeight:'60vh',background:'#fff',borderRadius:'8px',
      boxShadow:'0 0 12px rgba(0,0,0,.15)',overflow:'hidden',zIndex:9999
    });
    panel.innerHTML = `
      <div id="jj-messages" style="padding:12px;overflow-y:auto;
            height:calc(60vh - 90px);font:14px/1.4 sans-serif"></div>
      <div style="display:flex;border-top:1px solid #eee">
        <input id="jj-msg" placeholder="Ask me…" style="flex:1;border:none;padding:10px">
        <button id="jj-send" style="border:none;padding:10px;background:#2563eb;color:#fff">➤</button>
      </div>`;
    document.body.appendChild(panel);

    bubble.onclick = () => {
      panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    };

    /* ----------  Load @gradio/client as a module  ---------- */
    (async () => {
      let gr;                                    // will hold the connected client or stub
      try {
        const { Client } = await import('https://cdn.jsdelivr.net/npm/@gradio/client/dist/index.min.js');
        gr = await Client.connect(`${window.location.origin}/chat`);
        console.log('✅ chat backend connected');
      } catch (err) {
        console.warn('⚠️ chat backend unreachable – demo mode', err);
        gr = { predict: async () => ({ data: ['(server offline)'] }) };
        document.getElementById('jj-msg').placeholder = 'Josh Chat Offline';
      }

      /* ----------  Send handler (works live or stub)  ---------- */
      async function send() {
        const box = document.getElementById('jj-msg');
        if (!box.value.trim()) return;
        append(box.value, 'user'); const q = box.value; box.value = '';
        const res = await gr.predict('/chat', { message:{ text:q, files:[] } });
        append(res.data.join('\n'), 'bot');
      }
      document.getElementById('jj-send').onclick = send;
      document.getElementById('jj-msg').addEventListener('keypress', e => {
        if (e.key === 'Enter') send();
      });

      function append(text, who) {
        const div = document.createElement('div');
        div.textContent = text;
        div.style = `margin:6px 0;white-space:pre-wrap;
          font:${who==='user'?'600':'400'} 14px/1.4 sans-serif;
          color:${who==='user'?'#111':'#2563eb'}`;
        document.getElementById('jj-messages').appendChild(div);
      }
    })();
  </script>
<?php } );

