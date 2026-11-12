<?php
// chatbot.php (FINAL STABLE VERSION)
?>

<style>
#chatbot-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    font-size: 24px;
    cursor: pointer;
    z-index: 99999;
}

#chatbot-box {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 300px;
    height: 400px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    display: none;
    flex-direction: column;
    z-index: 99999;
}

#chat-header {
    background: #007bff;
    color: white;
    padding: 10px;
    border-radius: 10px 10px 0 0;
    font-weight: bold;
}

#chat-body {
    height: 300px;
    padding: 10px;
    overflow-y: auto;
    font-size: 14px;
}

#chat-input {
    display: flex;
    border-top: 1px solid #ddd;
}

#chat-input input {
    flex: 1;
    border: none;
    padding: 10px;
    outline: none;
}

#chat-input button {
    border: none;
    background: #007bff;
    color: white;
    padding: 10px;
    cursor: pointer;
}
</style>

<!-- BUTTON -->
<button id="chatbot-btn">💬</button>

<!-- CHAT BOX -->
<div id="chatbot-box">
    <div id="chat-header">Assistant</div>

    <div id="chat-body"></div>

    <div id="chat-input">
        <input type="text" id="userMsg" placeholder="Type message...">
        <button id="sendBtn">Send</button>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // prevent duplicate
    if (window.chatbotLoaded) return;
    window.chatbotLoaded = true;

    const btn = document.getElementById("chatbot-btn");
    const box = document.getElementById("chatbot-box");
    const input = document.getElementById("userMsg");
    const body = document.getElementById("chat-body");
    const sendBtn = document.getElementById("sendBtn");

    if (!btn || !box) return;

    let isOpen = false;

    // TOGGLE CHAT
    btn.addEventListener("click", function () {
        isOpen = !isOpen;
        box.style.display = isOpen ? "block" : "none";
    });

    // SEND MESSAGE
    function sendMsg() {
        let msg = input.value.trim();
        if (msg === "") return;

        body.innerHTML += "<div><b>You:</b> " + msg + "</div>";

        let reply = getBotReply(msg);

        body.innerHTML += "<div style='color:green'><b>Bot:</b> " + reply + "</div>";

        input.value = "";
        body.scrollTop = body.scrollHeight;
    }

    sendBtn.addEventListener("click", sendMsg);

    input.addEventListener("keypress", function(e) {
        if (e.key === "Enter") sendMsg();
    });

    // SIMPLE BOT LOGIC
    function getBotReply(msg) {
        msg = msg.toLowerCase();

        if (msg.includes("appointment")) {
            return "Go to Book Appointment page.";
        }
        if (msg.includes("doctor")) {
            return "Select a doctor to view slots.";
        }
        if (msg.includes("prescription")) {
            return "Check My Prescriptions section.";
        }
        if (msg.includes("hi") || msg.includes("hello")) {
            return "Hello 👋 How can I help you?";
        }

        return "Try asking about appointments or doctors.";
    }

});
</script>