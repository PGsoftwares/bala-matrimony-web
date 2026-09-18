@extends('web.layouts.layout')
@section('title', $metaTags->title ?? '')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid py-4 bg-light-primary">
        <div class="row g-3">

            <!-- Sidebar: My Chats -->
            <div class="col-md-4 mb-md-5 mb-0">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-3">
                        <h6 class="fw-bold mb-3">My Chats</h6>

                        <!-- Chat List -->
                        <div class="list-group">
                            @foreach($chatUsers as $user)
                                <a href="#" class="list-group-item list-group-item-action chat-user-link d-flex align-items-center rounded-3 mb-2"
                                   data-id="{{ $user->user_id }}">
                                    <img src="{{ $user->profile_image }}" class="rounded-circle me-2" width="40" height="40" alt="">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        <small class="primary_color">{{ $unreadCounts[$user->user_id] ?? 0 }} new messages</small>
                                    </div>
                                    <div class="ms-auto">
                                        <i class="bi bi-chevron-right"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Window -->
            <div id="chatWindow" class="col-md-8 mb-5 mb-md-0">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <div class="card border-0 shadow-sm rounded-3 d-flex flex-column h-100 justify-content-center align-items-center text-muted">
                    <div class="text-center">
                        <i class="bi bi-chat-dots fs-1"></i>
                        <p class="mt-2">Select a chat to view messages</p>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chatLinks = document.querySelectorAll('.chat-user-link');
            const authUserId = @json(Auth::id());
            const openChatUserId = @json($openChatUserId);

            // Attach click listeners
            chatLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    const userId = this.dataset.id;

                    // Highlight selected
                    chatLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');

                    const details = fetch(`chat-details/${userId}`)
                        .then(res => res.json())
                        .then(data => renderChatWindow(data));
                });
            });

            // Auto-open chat if openChatUserId is set, otherwise open the first chat
            if (chatLinks.length > 0) {
                let targetChat = null;

                if (openChatUserId) {
                    targetChat = document.querySelector(`.chat-user-link[data-id="${openChatUserId}"]`);
                }

                if (!targetChat) {
                    targetChat = chatLinks[0];
                }

                targetChat?.click();
            }

            // Helper functions
            function isSameDate(d1, d2) {
                return d1.getFullYear() === d2.getFullYear() &&
                    d1.getMonth() === d2.getMonth() &&
                    d1.getDate() === d2.getDate();
            }

            function formatDate(date) {
                const d = new Date(date);
                const day = String(d.getDate()).padStart(2, '0');
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const month = monthNames[d.getMonth()];
                const year = d.getFullYear();
                return `${day} ${month} ${year}`;
            }

            function groupMessagesByDate(messages) {
                const grouped = {};
                const today = new Date();
                const yesterday = new Date();
                yesterday.setDate(today.getDate() - 1);

                messages.forEach(msg => {
                    const msgDate = new Date(msg.created_at);
                    let label;

                    if (isSameDate(msgDate, today)) {
                        label = 'Today';
                    } else if (isSameDate(msgDate, yesterday)) {
                        label = 'Yesterday';
                    } else {
                        label = formatDate(msgDate);
                    }

                    if (!grouped[label]) grouped[label] = [];
                    grouped[label].push(msg);
                });

                return grouped;
            }

            function renderChatWindow(data) {
                const chatWindow = document.getElementById('chatWindow');
                const groupedMessages = groupMessagesByDate(data.messages);
                let messagesHtml = '';

                for (const date in groupedMessages) {
                    messagesHtml += `<div class="text-center mb-3 small text-muted">${date}</div>`;
                    groupedMessages[date].forEach(msg => {
                        const isMe = msg.user_id === authUserId;
                        messagesHtml += `
                        <div class="d-flex ${isMe ? 'justify-content-end' : 'justify-content-start'} mb-2">
                            <div class="position-relative button2 px-3 py-2 rounded-4 small shadow-sm" style="max-width: 75%;">
                                <div>${msg.messages}</div>
                                <div class="text-white-50 small mt-1 text-end" style="font-size: 0.7rem;">
                                    ${new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </div>
                            </div>
                        </div>
                    `;
                    });
                }

                chatWindow.innerHTML = `
                <div class="card border-0 shadow-sm rounded-3 d-flex flex-column h-100">
                    <div class="card-header bg-white d-flex align-items-center">
                        <img src="${data.user.profile_image}" class="rounded-circle me-2" width="40" height="40" alt="">
                        <h6 class="mb-0 fw-semibold">${data.user.name}</h6>
                    </div>
                    <div class="card-body flex-grow-1 overflow-auto px-3" id="chatMessages" style="height: 300px">
                        ${messagesHtml}
                    </div>
                    <div class="card-footer bg-white">
                        <div id="chatErrorBox" class="mb-2"></div>
                        <form id="chatForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="${authUserId}">
                            <input type="hidden" name="chat_user_id" value="${data.user.user_id}">
                            <div class="input-group px-3 py-2">
                                <input type="text" name="message" id="messageInput" class="form-control rounded px-2 py-3 border-end-0" placeholder="Type your message">
                                <button type="submit" class="btn bg-light-primary text-white px-4">
                                    <i class="bi bi-send-fill primary_color"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;

                attachChatFormSubmit();
            }

            function attachChatFormSubmit() {
                const form = document.getElementById('chatForm');
                const messageInput = document.getElementById('messageInput');
                const chatMessages = document.getElementById('chatMessages');
                const errorBox = document.getElementById('chatErrorBox');

                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    errorBox.innerHTML = '';
                    const formData = new FormData(form);

                    fetch(@json(route('chats.store')), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                        .then(async (res) => {
                            if (!res.ok) {
                                const errorData = await res.json();
                                throw new Error(errorData.error || 'Something went wrong');
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const msgEl = document.createElement('div');
                                msgEl.className = 'd-flex flex-column align-items-end mb-3';
                                msgEl.innerHTML = `
                                <div class="bg-danger text-white px-3 py-2 rounded-4 small shadow-sm d-inline-block">
                                    ${data.message.messages}
                                </div>
                                <small class="text-muted mt-1" style="font-size: 0.7rem;">
                                    ${new Date(data.message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </small>
                            `;
                                chatMessages.appendChild(msgEl);
                                chatMessages.scrollTop = chatMessages.scrollHeight;
                                messageInput.value = '';
                            }
                        })
                        .catch(error => {
                            errorBox.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                ${error.message}
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        });
                });
            }
        });
    </script>


    @include('web.includes.footer')
@endsection
