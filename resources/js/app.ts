import '../css/app.css';

declare global {
    interface Window {
        toggleLike: (tweetId: number, btn: HTMLElement) => Promise<void>;
        toggleFollow: (userId: number, btn: HTMLElement) => Promise<void>;
        copyTweetLink: (url: string) => void;
        openEditTweetModal: (tweetId: number, message: string) => void;
        closeEditTweetModal: () => void;
        openDeleteModal: (actionUrl: string) => void;
        closeDeleteModal: () => void;
        openEditProfileModal: () => void;
        closeEditProfileModal: () => void;
        openComposeModal: () => void;
        closeComposeModal: () => void;
        showToast: (msg: string) => void;
    }
}

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    return meta ? meta.content : '';
}

// Global Toast message notification
window.showToast = (msg: string) => {
    let toast = document.getElementById('global-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'global-toast';
        toast.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 bg-[#1d9bf0] text-white font-medium text-sm px-5 py-3 rounded-full shadow-2xl z-50 transition-opacity duration-300 opacity-0 pointer-events-none';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.remove('opacity-0');
    toast.classList.add('opacity-100');

    setTimeout(() => {
        if (toast) {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
        }
    }, 2500);
};

// Copy Tweet Link
window.copyTweetLink = (url: string) => {
    navigator.clipboard.writeText(url).then(() => {
        window.showToast('Copied link to clipboard!');
    }).catch(() => {
        window.showToast('Copied link!');
    });
};

// AJAX Like Toggle
window.toggleLike = async (tweetId: number, btn: HTMLElement) => {
    try {
        const res = await fetch(`/tweets/${tweetId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        if (res.status === 401) {
            window.location.href = '/login';
            return;
        }

        if (res.ok) {
            const data = (await res.json()) as { liked: boolean; likes_count: number };
            const heartSvg = btn.querySelector('svg');
            const countEl = btn.querySelector('.like-count');

            if (data.liked) {
                btn.classList.add('text-[#f91880]');
                btn.classList.remove('text-[#71767b]');
                if (heartSvg) {
                    heartSvg.setAttribute('fill', 'currentColor');
                    heartSvg.classList.add('animate-heart-pop');
                    setTimeout(() => heartSvg.classList.remove('animate-heart-pop'), 400);
                }
            } else {
                btn.classList.remove('text-[#f91880]');
                btn.classList.add('text-[#71767b]');
                if (heartSvg) {
                    heartSvg.setAttribute('fill', 'none');
                }
            }

            if (countEl) {
                countEl.textContent = data.likes_count > 0 ? String(data.likes_count) : '';
            }
        }
    } catch (e) {
        console.error('Like toggle error:', e);
    }
};

// Helper for Follow / Unfollow Button Hover Styles
function updateFollowButton(btn: HTMLElement, isFollowing: boolean): void {
    btn.setAttribute('data-following', isFollowing ? 'true' : 'false');
    if (isFollowing) {
        btn.textContent = 'Following';
        btn.className = 'follow-btn shrink-0 bg-transparent text-white font-bold text-xs py-1.5 px-4 rounded-full border border-[#536471] hover:border-[#f4212e] hover:text-[#f4212e] hover:bg-[#f4212e]/10 transition duration-150 cursor-pointer';
        btn.onmouseenter = () => { btn.textContent = 'Unfollow'; };
        btn.onmouseleave = () => { btn.textContent = 'Following'; };
    } else {
        btn.textContent = 'Follow';
        btn.className = 'follow-btn shrink-0 bg-white hover:bg-[#e6e6e6] text-black font-bold text-xs py-1.5 px-4 rounded-full transition duration-150 cursor-pointer';
        btn.onmouseenter = null;
        btn.onmouseleave = null;
    }
}

// AJAX Follow Toggle
window.toggleFollow = async (userId: number, btn: HTMLElement) => {
    try {
        const res = await fetch(`/users/${userId}/follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        if (res.status === 401) {
            window.location.href = '/login';
            return;
        }

        if (res.ok) {
            const data = (await res.json()) as { following: boolean; followers_count?: number };
            updateFollowButton(btn, data.following);

            const followerCountEl = document.getElementById('profile-follower-count');
            if (followerCountEl && data.followers_count !== undefined) {
                followerCountEl.textContent = String(data.followers_count);
            }
        }
    } catch (e) {
        console.error('Follow toggle error:', e);
    }
};

// Initialize follow buttons on page load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('.follow-btn').forEach((btn) => {
        const isFollowing = btn.getAttribute('data-following') === 'true';
        updateFollowButton(btn, isFollowing);
    });
});

// Modals
window.openEditTweetModal = (tweetId: number, message: string) => {
    const modal = document.getElementById('edit-tweet-modal');
    const form = document.getElementById('edit-tweet-form') as HTMLFormElement | null;
    const textarea = document.getElementById('edit-tweet-message') as HTMLTextAreaElement | null;
    const countEl = document.getElementById('edit-tweet-char-count');

    if (modal && form && textarea) {
        form.action = `/tweets/${tweetId}`;
        textarea.value = message;
        if (countEl) {
            countEl.textContent = String(280 - message.length);
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        textarea.focus();
        textarea.dispatchEvent(new Event('input'));
    }
};

window.closeEditTweetModal = () => {
    const modal = document.getElementById('edit-tweet-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

window.openDeleteModal = (actionUrl: string) => {
    const modal = document.getElementById('delete-tweet-modal');
    const form = document.getElementById('delete-tweet-form') as HTMLFormElement | null;
    if (modal && form) {
        form.action = actionUrl;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

window.closeDeleteModal = () => {
    const modal = document.getElementById('delete-tweet-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

window.openEditProfileModal = () => {
    const modal = document.getElementById('edit-profile-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
};

window.closeEditProfileModal = () => {
    const modal = document.getElementById('edit-profile-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

window.openComposeModal = () => {
    const modal = document.getElementById('compose-modal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        const textarea = modal.querySelector('textarea');
        if (textarea) textarea.focus();
    }
};

window.closeComposeModal = () => {
    const modal = document.getElementById('compose-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
};

// Auto character counter setup
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLTextAreaElement>('textarea[data-countdown]').forEach((textarea) => {
        const max = parseInt(textarea.getAttribute('data-max') || '280', 10);
        const targetId = textarea.getAttribute('data-countdown');
        const submitBtnId = textarea.getAttribute('data-submit-btn');
        const countEl = targetId ? document.getElementById(targetId) : null;
        const submitBtn = submitBtnId ? (document.getElementById(submitBtnId) as HTMLButtonElement | null) : null;

        const update = () => {
            const remaining = max - textarea.value.length;
            if (countEl) {
                countEl.textContent = String(remaining);
                if (remaining < 0) {
                    countEl.className = 'text-xs font-bold text-[#f4212e]';
                } else if (remaining <= 20) {
                    countEl.className = 'text-xs font-bold text-[#ffd400]';
                } else {
                    countEl.className = 'text-xs font-medium text-[#71767b]';
                }
            }
            if (submitBtn) {
                submitBtn.disabled = textarea.value.trim().length === 0 || remaining < 0;
            }
        };

        textarea.addEventListener('input', update);
        update();
    });
});

export {};
