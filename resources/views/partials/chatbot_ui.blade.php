{{-- resources/views/partials/chatbot.blade.php --}}

{{-- Biểu tượng chatbot để mở/đóng chatbox --}}
<div class="chatbot-icon" onclick="toggleChatbox()">💬</div>

{{-- Container chính của chatbox --}}
<div id="chatbox-container">
    <div id="chatbox-header">
        Chat với Tourgether
        <span class="close-button" onclick="toggleChatbox()">×</span>
    </div>
    <div id="chatbox">
        {{-- Các tin nhắn và form yêu cầu gọi lại sẽ được thêm vào đây bằng JavaScript --}}
    </div>
    <div id="input-area">
        <input type="text" id="userInput" placeholder="Nhập tin nhắn...">
        <button id="sendButton">Gửi</button>
    </div>
</div>

{{-- Popup hiển thị danh sách tour --}}
<div id="tour-popup-overlay" class="tour-popup-overlay">
    <div class="tour-popup-content">
        <span class="popup-close-button" onclick="closeTourPopup()">×</span>
        <h3>Các Tour Hiện Có</h3>
        <div id="tour-list-container">
            {{-- Danh sách tour sẽ được điền vào đây bằng JavaScript --}}
        </div>
    </div>
</div>