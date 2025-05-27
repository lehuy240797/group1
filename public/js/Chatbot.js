// public/js/chatbot.js

const chatboxContainer = document.getElementById('chatbox-container');
const chatbox = document.getElementById('chatbox');
const userInput = document.getElementById('userInput');
const sendButton = document.getElementById('sendButton');
const tourPopupOverlay = document.getElementById('tour-popup-overlay');
const tourListContainer = document.getElementById('tour-list-container');

let isChatboxInitialized = false;

function toggleChatbox() {
    if (chatboxContainer.style.display === 'none' || chatboxContainer.style.display === '') {
        chatboxContainer.style.display = 'flex';
        if (!isChatboxInitialized) {
            displayWelcomeMessage();
            isChatboxInitialized = true;
        }
    } else {
        chatboxContainer.style.display = 'none';
        closeTourPopup(); // Đảm bảo popup tour được đóng khi đóng chatbox
    }
}

function displayWelcomeMessage() {
    const welcomeHtml = `
        <p>Chào bạn! Mình là Chatbot hỗ trợ tìm kiếm tour du lịch. 😊</p>
        <p>Mình có thể giúp bạn tìm kiếm tour theo:</p>
        <ul>
            <li>💰 <b>Giá</b>: Thử hỏi "tour giá khoảng 9 triệu".</li>
            <li>🏖️ <b>Điểm đến</b>: Hỏi "tour ở Vũng Tàu".</li>
        </ul>
        <p>Hoặc bạn có thể chọn nhanh các gợi ý dưới đây:</p>
    `;
    const replies = [
        { text: 'Tour Đà Nẵng', value: 'tour Đà Nẵng' },
        { text: 'Tour HCM - Vũng Tàu', value: 'tour HCM - Vũng Tàu' },
        { text: 'Tour HCM - Nha Trang', value: 'tour HCM - Nha Trang' },
        { text: 'Tour Cao Hơn 3 Triệu', value: 'tour cao hơn 3 triệu' },
        { text: 'Tất cả tour hiện có', value: 'tất cả tour hiện có' },
        { text: 'Liên hệ tư vấn', value: 'tôi muốn nói chuyện với nhân viên' }
    ];
    const botMsg = document.createElement('div');
    botMsg.classList.add('bot-message');
    botMsg.innerHTML = welcomeHtml;
    chatbox.appendChild(botMsg);
    appendQuickReplies(botMsg, replies);
    chatbox.scrollTop = chatbox.scrollHeight;
}

function appendQuickReplies(el, replies) {
    const replyDiv = document.createElement('div');
    replyDiv.classList.add('quick-replies');
    replies.forEach(r => {
        const btn = document.createElement('button');
        btn.classList.add('quick-reply-btn');
        btn.textContent = r.text;
        btn.dataset.message = r.value;
        btn.onclick = () => {
            userInput.value = btn.dataset.message;
            sendMessage();
        };
        replyDiv.appendChild(btn);
    });
    el.appendChild(replyDiv);
}

function appendMessage(sender, messageData) {
    const div = document.createElement('div');
    div.classList.add(sender === 'Bạn' ? 'user-message' : 'bot-message');

    if (sender === 'Bạn') {
        div.innerHTML = `<p>${messageData}</p>`;
    } else {
        let content = `<p>${messageData.text ? messageData.text.replace(/\n/g, '<br>') : ''}</p>`;

        if (messageData.request_callback_form) {
            content += `
                <div class="callback-form-in-chat">
                    <form id="callback-form-in-chat">
                        <div class="form-group">
                            <label for="callback-name-in-chat">Họ và tên của bạn:</label>
                            <input type="text" id="callback-name-in-chat" required>
                        </div>
                        <div class="form-group">
                            <label for="callback-email-in-chat">Email (tùy chọn):</label>
                            <input type="email" id="callback-email-in-chat">
                        </div>
                        <div class="form-group">
                            <label for="callback-phone-in-chat">Số điện thoại của bạn:</label>
                            <input type="tel" id="callback-phone-in-chat" required>
                        </div>
                        <button type="submit" class="callback-submit-button-in-chat">Gửi yêu cầu</button>
                    </form>
                    <button type="button" class="cancel-callback-button">Hủy bỏ</button> </div>
            `;
            div.classList.add('callback-form-message');
            div.innerHTML = content;

            setTimeout(() => {
                const formInChat = document.getElementById('callback-form-in-chat');
                if (formInChat) {
                    formInChat.addEventListener('submit', submitCallbackRequestInChat);
                }
                const cancelButton = div.querySelector('.cancel-callback-button'); // Tìm nút Hủy bỏ
                if (cancelButton) {
                    cancelButton.addEventListener('click', cancelCallbackForm); // Thêm sự kiện click
                }
            }, 0);

        } else if (messageData.tours_list && messageData.tours_list.length > 0) {
            showTourPopup(messageData.tours_list);
            div.innerHTML = content;
        } else if (messageData.tour_id) {
            content += `<a href="/tour-details/${messageData.tour_id}" target="_blank" class="view-tour-button">Xem chi tiết tour</a>`;
            div.innerHTML = content;
        } else {
            div.innerHTML = content;
        }

        // Chỉ append quick replies nếu có trong messageData và không phải là form callback (hoặc nếu form callback có quick_replies khác)
        if (messageData.quick_replies && messageData.quick_replies.length > 0) {
            appendQuickReplies(div, messageData.quick_replies);
        }
    }

    chatbox.appendChild(div);
    chatbox.scrollTop = chatbox.scrollHeight;
}

// Hàm mới để xử lý hủy bỏ form
function cancelCallbackForm() {
    const formMessageElement = document.querySelector('.bot-message.callback-form-message');
    if (formMessageElement) {
        formMessageElement.remove(); // Xóa phần tử chứa form
    }
    // Hiển thị tin nhắn xác nhận hủy bỏ
    appendMessage('Bot', {
        text: 'Bạn đã hủy yêu cầu gọi lại.'
        // ,
        // quick_replies: [ // Gợi ý mới sau khi hủy, không bao gồm nút "Hủy bỏ"
        //     { text: 'Tìm tour mới', value: 'tất cả tour hiện có' },
        //     { text: 'Liên hệ tư vấn', value: 'tôi muốn nói chuyện với nhân viên' }
        // ]
    });
}


function sendMessage() {
    const message = userInput.value.trim();
    if (!message) return;

    // Đã bỏ logic xử lý 'hủy_form_callback' ở đây vì nút "Hủy bỏ" không còn là quick reply
    // và không gửi tin nhắn đến backend nữa.

    appendMessage('Bạn', message);
    userInput.value = '';

    fetch('/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
        if (data.reply) {
            // Khi không tìm thấy tour, bot trả về một tin nhắn và quick_replies
            // Đảm bảo không có quick_reply 'hủy' được thêm vào trong trường hợp này.
            if (!data.reply.text && !data.reply.request_callback_form && !(data.reply.tours_list && data.reply.tours_list.length > 0) && !data.reply.tour_id) {
                 data.reply.text = "❓ Xin lỗi, tôi không tìm thấy tour hoặc thông tin phù hợp với yêu cầu của bạn. Bạn có muốn thử lại với các gợi ý sau không?";
                 if (!data.reply.quick_replies || data.reply.quick_replies.length === 0) {
                      data.reply.quick_replies = [
                          { text: 'Tour Đà Nẵng', value: 'tour Đà Nẵng' },
                          { text: 'Tất cả tour hiện có', value: 'tất cả tour hiện có' },
                          { text: 'Liên hệ tư vấn', value: 'tôi muốn nói chuyện với nhân viên' }
                      ];
                 }
            }
            appendMessage('Bot', data.reply);
        } else {
            appendMessage('Bot', { text: 'Lỗi kết nối hoặc phản hồi không hợp lệ từ server. Vui lòng thử lại sau.' });
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        appendMessage('Bot', { text: 'Lỗi kết nối. Vui lòng thử lại sau.' });
    });
}

function showTourPopup(tours) {
    tourListContainer.innerHTML = '';
    if (tours.length === 0) {
        tourListContainer.innerHTML = '<p>Không tìm thấy tour nào.</p>';
    } else {
        tours.forEach(tour => {
            const tourItem = document.createElement('div');
            tourItem.classList.add('tour-popup-item');
            tourItem.innerHTML = `
                <h4>${tour.name_tour}</h4>
                <p><b>Điểm đến:</b> ${tour.location}</p>
                <p><b>Giá:</b> ${parseFloat(tour.price).toLocaleString('vi-VN')} VND</p>
                <a href="/tour-details/${tour.id}" target="_blank" class="popup-view-detail">Xem chi tiết</a>
            `;
            tourListContainer.appendChild(tourItem);
        });
    }
    tourPopupOverlay.style.display = 'flex';
}

function closeTourPopup() {
    tourPopupOverlay.style.display = 'none';
}

function submitCallbackRequestInChat(event) {
    event.preventDefault();

    const nameInput = document.getElementById('callback-name-in-chat');
    const emailInput = document.getElementById('callback-email-in-chat');
    const phoneInput = document.getElementById('callback-phone-in-chat');

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const phone = phoneInput.value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^(0|\+84)\d{9,10}$/; 

    if (!name) {
        alert('Vui lòng nhập họ và tên của bạn.');
        nameInput.focus();
        return;
    }
    if (!phone) {
        alert('Vui lòng nhập số điện thoại của bạn.');
        phoneInput.focus();
        return;
    }
    if (!phoneRegex.test(phone)) {
        alert('Số điện thoại không hợp lệ. Vui lòng nhập đúng định dạng (ví dụ: 0912345678 hoặc +84912345678).');
        phoneInput.focus();
        return;
    }
    if (email && !emailRegex.test(email)) {
        alert('Email không hợp lệ. Vui lòng kiểm tra lại.');
        emailInput.focus();
        return;
    }

    const form = document.getElementById('callback-form-in-chat');
    if (form) {
        form.style.pointerEvents = 'none';
        form.querySelector('button[type="submit"]').textContent = 'Đang gửi...';
        form.querySelector('button[type="submit"]').disabled = true;
    }

    const formMessageElement = form.closest('.bot-message.callback-form-message');

    fetch('/api/callback-request', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ name, email, phone })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (formMessageElement) {
                formMessageElement.remove();
            }
            appendMessage('Bot', {
                text: `Cảm ơn bạn **${name}**! Chúng tôi đã nhận được yêu cầu của bạn và sẽ liên hệ lại qua số điện thoại **${phone}** sớm nhất có thể.`,
              
            });
        } else {
            alert('Có lỗi xảy ra khi gửi yêu cầu. Vui lòng thử lại.');
            console.error('Callback request failed:', data.message);
            if (form) {
                form.style.pointerEvents = 'auto';
                form.querySelector('button[type="submit"]').textContent = 'Gửi yêu cầu';
                form.querySelector('button[type="submit"]').disabled = false;
            }
        }
    })
    .catch(error => {
        console.error('Error submitting callback request:', error);
        alert('Lỗi kết nối. Vui lòng thử lại sau.');
        if (form) {
            form.style.pointerEvents = 'auto';
            form.querySelector('button[type="submit"]').textContent = 'Gửi yêu cầu';
            form.querySelector('button[type="submit"]').disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    sendButton.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
    
    chatboxContainer.style.display = 'none';
});